<?php

namespace App\Services\CMS;

use App\Exceptions\CMS\ImportStudentException;
use App\Repositories\User\UserInterface;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StudentService extends BaseService
{
    const IMPORT_STUDENT_NUMBER_OF_FIELDS = 5;
    public function __construct(
        public UserInterface $userRepository,
    ) {}

    public function getPaginateStudents()
    {
        return $this->userRepository->getPaginateStudents();
    }

    public function getStudent($id)
    {
        return $this->userRepository->find($id);
    }

    public function getPickupStudents()
    {
        return $this->userRepository->all();
    }

    public function storeStudent($studentPayload)
    {
        $studentPayload['password'] = Hash::make($studentPayload['password']);
        $studentPayload['created_by'] = Auth::user()->id;

        return $this->userRepository->create($studentPayload);
    }

    public function updateStudent($studentPayload, $id)
    {
        if (!empty($studentPayload['new_password'])) {
            $studentPayload['password'] = Hash::make($studentPayload['new_password']);
            unset($studentPayload['new_password']);
        }

        return $this->userRepository->update($studentPayload, $id);
    }

    /**
     * @throws ImportStudentException
     */
    public function executeImportStudents(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();

        $rows = match ($extension) {
            'csv' => $this->readCsv($file->getRealPath()),
            'xls', 'xlsx' => $this->readExcel($file->getRealPath()),
            default => [],
        };

        if (empty($rows)) {
            throw new BadRequestHttpException('The file is empty or could not be read.');
        }

        $validRows = [];
        $errors = [];
        $codes = [];
        $emails = [];
        // for self check duplicate
        $codeSet = [];
        $emailSet = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2; // assuming first line is header

            if (count($row) < self::IMPORT_STUDENT_NUMBER_OF_FIELDS) {
                $errors[] = "Line {$line}: Missing data.";
                continue;
            }

            $hasErrors = false;

            [$code, $email, $name, $password, $searchPrefix] = array_slice($row, 0, self::IMPORT_STUDENT_NUMBER_OF_FIELDS);

            // Simple row validation
            $validator = Validator::make([
                'code' => $code,
                'email' => $email,
                'name' => $name,
                'password' => $password,
                'search_prefix' => $searchPrefix,
            ], [
                'code' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'name' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'search_prefix' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                $errors[] = "Line {$line}: " . implode(' ', $validator->errors()->all());
                $hasErrors = true;
            }

            $code = trim($code);
            $email = strtolower(trim($email));

            // self check duplicate of request payload
            if (in_array($code, $codeSet)) {
                $errors[] = "Line {$line}: Code '{$code}' is duplicated in the file.";
                $hasErrors = true;
            }
            if (in_array($email, $emailSet)) {
                $errors[] = "Line {$line}: Email '{$email}' is duplicated in the file.";
                $hasErrors = true;
            }

            $codeSet[] = $code;
            $emailSet[] = $email;

            if ($hasErrors) continue;

            // for check duplicate in DB
            $codes[] = $code;
            $emails[] = $email;

            $validRows[] = [
                'code' => $code,
                'email' => $email,
                'name' => $name,
                'password' => $password,
                'search_prefix' => $searchPrefix,
                'line' => $line
            ];
        }

        // Check duplicates in DB
        $existingCodes = $this->userRepository->findWhereIn('code', $codes)->pluck('code')->toArray();
        $existingEmails =  $this->userRepository->findWhereIn('email', $emails)->pluck('email')->toArray();

        $insertData = [];
        $createdBy = auth()->id();
        foreach ($validRows as $row) {
            if (in_array($row['code'], $existingCodes)) {
                $errors[] = "Line {$row['line']}: Code '{$row['code']}' already exists.";
                continue;
            }

            if (in_array($row['email'], $existingEmails)) {
                $errors[] = "Line {$row['line']}: Email '{$row['email']}' already exists.";
                continue;
            }

            $insertData[] = [
                'code' => $row['code'],
                'email' => $row['email'],
                'name' => $row['name'],
                'password' => Hash::make($row['password']),
                'is_active' => true,
                'created_by' => $createdBy,
                'search_prefix' => $row['search_prefix'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($errors) {
            throw new ImportStudentException($errors);
        }

        $insertDataCount = count($insertData);
        if ($insertDataCount) {
            $this->userRepository->insert($insertData);
        }

        return $insertDataCount;
    }

    protected function readCsv($path)
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $headerSkipped = false;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }

    protected function readExcel($path)
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($sheet->getRowIterator() as $i => $row) {
            if ($i === 1) continue; // skip header
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = trim((string) $cell->getValue());
            }
            $rows[] = $data;
        }

        return $rows;
    }

    public function getHistoryStudentsOfTest($testId)
    {
        return $this->userRepository->getHistoryStudentsOfTest($testId);
    }

    public function getExamSessionsOfStudentWithTestId($testId, $studentId)
    {
        return $this->userRepository->getExamSessionsOfStudentWithTestId($testId, $studentId);
    }
}
