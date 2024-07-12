@extends('layouts.app')

@section('title', config('urls.about_us.title'))

@section('content')
    <div class="flex flex-col justify-center items-center">
        <div class="min-h-[400px] md:min-h-[600px] flex flex-col col-md-6 col-1 justify-center items-center gap-8">
            <div class="p-6 m-6 bg-backgroundColor md:min-w-[450px] md:max-w-[800] flex flex-column gap-3">
                <h1>O nás</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                    into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                    release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                    software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
        </div>
    </div>
@endsection