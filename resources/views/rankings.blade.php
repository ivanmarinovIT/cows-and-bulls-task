<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rankings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Tries</th>
                            <th scope="col">Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($place = 0)
                        @foreach($rankings as $ranking)
                            @php($place++)
                            <tr>
                                <th scope="row">{{ $place }}</th>
                                <td>{{ $ranking->name }}</td>
                                <td>{{ $ranking->tries }}</td>
                                <td>{{ $ranking->number}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
