<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg border border-blue-300">
                <div class="p-8">

                    <!-- Grid Layout -->
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                        <!-- Card 1 -->
                        <div class="flex flex-col items-center justify-center bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md hover:shadow-lg transition p-6">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Awesome Trades Copier
                            </h5>
                            <hr class="w-12 my-2 border-blue-500">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                Copy Trades from MT4, MT5 & Telegram to MT4 and MT5 Platforms
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div class="flex flex-col items-center justify-center bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md hover:shadow-lg transition p-6">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Master Accounts
                            </h5>
                            <hr class="w-12 my-2 border-blue-500">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                Source of Trade Data for Client Accounts
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div class="flex flex-col items-center justify-center bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md hover:shadow-lg transition p-6">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Client Accounts
                            </h5>
                            <hr class="w-12 my-2 border-blue-500">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                Receives Trades from Master Accounts
                            </p>
                        </div>

                        <!-- Card 4 -->
                        <div class="flex flex-col items-center justify-center bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md hover:shadow-lg transition p-6">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Trade Data
                            </h5>
                            <hr class="w-12 my-2 border-blue-500">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                Shows Source and Client Trade Data
                            </p>
                        </div>

                    </div>

                </div>


                <hr/>

                <br/>
                <div class="container row">
                    <p>
                        <a href="#">
                            Swagger API Documentation
                        </a>
                    </p>
                </div>

                <br/>
            </div>
        </div>
    </div>
</x-app-layout>
