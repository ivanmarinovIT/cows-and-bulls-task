<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <!-- {{ session('number')}} tries: {{session('tries')}} -->
                    @if(session('result'))
                        <h1>
                            Bulls: {{ session('result.bulls') }} | Cows: {{ session('result.cows') }}
                            <br>
                        </h1>           
                        @if(session('result.success') == true)
                            <h1>
                                YOU WON!
                            </h1>
                            A new number was generated
                        @endif
                    @endif
                    
                    <form method="POST" action="/guess">
                        @csrf
                        <label for="guess">Enter your four digits:</label>
                        <input type="text" maxlength="4" pattern="\d{4}" id="guess" name="guess" value="{{ session('guess') ?: '' }}">
                        <button type="submit" class="btn btn-dark">Submit Guess</button>
                        <a href="{{ route('tryAgain') }}" class="btn btn-danger">Try again</a>
                    </form>
                    <div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
