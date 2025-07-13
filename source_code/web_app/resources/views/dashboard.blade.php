<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" 
            style="border: 0.5px solid blue;">
                <div class="p-6 text-gray-900 dark:text-gray-100 row">
               
                <div class="col-3">

                    <div class="card text-center">
                        <!-- <img src="https://cdn.icon-icons.com/icons2/2248/PNG/512/source_branch_icon_135166.png"
                        style="width: 100px; height: 100px;" class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">BR Box</h5>
                            <hr/>
                            <p class="card-text">Copy Trades from MT4, MT5 & Telegram to MT4 and MT5 Platforms</p>
                        </div>
                    </div>

                </div>

                <div class="col-3">

                    <div class="card text-center">
                        <!-- <img src="https://cdn.icon-icons.com/icons2/2248/PNG/512/source_branch_icon_135166.png"
                        style="width: 100px; height: 100px;" class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">Master Accounts</h5>
                            <hr/>
                            <p class="card-text">Source of Trade Data for Client Accounts</p>
                        </div>
                    </div>

                </div>


                <div class="col-3">
                    
                    <div class="card text-center">
                        <!-- <img src="https://cdn.icon-icons.com/icons2/2248/PNG/512/source_branch_icon_135166.png"
                        style="width: 100px; height: 100px;" class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">Client Accounts</h5>
                            <hr/>
                            <p class="card-text">Receives Trades from Master Accounts</p>
                        </div>
                    </div>

                </div>


                <div class="col-3">
                    <div class="card text-center">
                        <!-- <img src="https://cdn.icon-icons.com/icons2/2248/PNG/512/source_branch_icon_135166.png"
                        style="width: 100px; height: 100px;" class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">Trade Data</h5>
                            <hr/>
                            <p class="card-text">Shows Source and Client Trade Data</p>
                        </div>
                    </div>
                </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<style type="text/css">
    .card {
        height: 150px;
    }
</style>
