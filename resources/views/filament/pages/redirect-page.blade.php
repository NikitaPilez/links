<head>
    <!-- Остальные теги head -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<x-filament::page>
    <div class="mt-6 w-full">
        <div class="flex flex-col w-full">
            <div class="overflow-x-auto w-full">
                <form method="GET" action="{{ route('filament.admin.pages.redirect-page') }}" class="mb-4 space-y-4 px-4">
                    <div class="flex flex-wrap items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="items-center" style="margin: 10px">
                            <label for="mode" class="text-gray-700 font-medium">Группировать по:</label>
                            <div class="relative ml-2">
                                <select id="mode" name="mode" class="form-select block w-40 pl-3 pr-10 py-2 text-sm font-medium border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="date" {{ request('mode') == 'date' ? 'selected' : '' }}>Дата</option>
                                    <option value="geo" {{ request('mode') == 'geo' ? 'selected' : '' }}>Гео</option>
                                    <option value="blogger" {{ request('mode') == 'blogger' ? 'selected' : '' }}>Блоггер</option>
                                    <option value="scenario" {{ request('mode') == 'scenario' ? 'selected' : '' }}>Сценарий</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="items-center" style="margin: 10px">
                            <label for="date_from" class="text-gray-700 font-medium">Дата с:</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input block w-40 py-2 text-sm font-medium border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ml-2">
                        </div>
                        <div class="items-center" style="margin: 10px">
                            <label for="date_to" class="text-gray-700 font-medium">Дата до:</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input block w-40 py-2 text-sm font-medium border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ml-2">
                        </div>
                        <div x-data="{ open: false, selectedGeos: {{ json_encode(request('geo', [])) }} }" class="relative">
                            <button type="button" @click="open = !open" class="form-input block w-40 pl-3 pr-10 py-2 text-sm font-medium border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                Выберите гео
                                <svg x-bind:class="{ 'transform rotate-180': open, 'ml-2 h-4 w-4': true }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden z-10">
                                <div class="p-2 space-y-2">
                                    @foreach ($allGeos as $geo)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="geo[]" x-model="selectedGeos" value="{{ $geo }}" class="form-checkbox" :checked="selectedGeos.includes('{{ $geo }}')">
                                            <span class="ml-2 text-sm">{{ $geo }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-2 ml-2">Применить</button>
                </form>

                <div class="flex justify-end mb-4">
                    <form method="GET" action="{{ route('filament.admin.pages.export-csv') }}">
                        <input type="hidden" name="mode" value="{{ request('mode') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        <input type="hidden" name="geo" value="{{ json_encode(request('geo', [])) }}">
                        <button type="submit" class="btn btn-primary">Выгрузка в CSV</button>
                    </form>
                </div>

                <div class="py-2 align-middle inline-block min-w-full w-full">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg w-full">
                        <table class="min-w-full w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($mode === 'date')
                                        Дата
                                    @elseif($mode === 'geo')
                                        Гео
                                    @elseif($mode === 'blogger')
                                        Блоггер
                                    @elseif($mode === 'scenario')
                                        Сценарий
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Количество
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($redirects as $redirect)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $redirect->field }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $redirect->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex justify-center">
                    {{ $redirects->links() }}
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
