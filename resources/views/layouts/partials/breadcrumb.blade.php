@if(!empty($breadcrumbs))
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            @foreach($breadcrumbs as $breadcrumb => $url)
                @if(!empty($url))
                    <li class="breadcrumb-item">
                        <a href="{{ $url }}">{{ $breadcrumb }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $breadcrumb }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif