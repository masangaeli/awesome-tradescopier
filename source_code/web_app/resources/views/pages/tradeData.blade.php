<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trade Data') }}
        </h2>
    </x-slot>

    <div class="py-12 container">

        <div class="">

            <div class="col-2">
                <button class="btn btn-primary form-control"
                onclick="deleteAllTradeData()">Delete All Trade Data</button>
            </div>

        </div>
        <br/>
        
        <table class="table table-bordered table-striped table-hovered">
            <thead>
                <tr>
                    <td>S/N</td>
                    <td>Trade Master</td>
                    <td>Trade Client</td>
                    <td>Symbol</td>
                    <td>Type</td>
                    <td>Lot Size</td>
                    <td>Open Price</td>
                    <td>SL Price</td>
                    <td>TP Price</td>
                    <td>Created From Source</td>
                    <td>Copied To Client</td>
                    <td>Actions</td>
                </tr>
            </thead>

            <tbody>
                @php $id = 0; @endphp
                @foreach($tradeDatas as $tradeData)
                <tr>
                    <td>{{ $id += 1 }}</td>
                    <td>{{ @$tradeData->tradeMaster->masterTitle }}</td>
                    <td>{{ @$tradeData->tradeClient->clientTitle }}</td>
                    <td>{{ $tradeData->symbol }}</td>
                    <td>{{ $tradeData->tradeType }}</td>
                    <td>{{ $tradeData->lotSize }}</td>
                    <td>{{ $tradeData->openPrice }}</td>
                    <td>{{ $tradeData->slPrice }}</td>
                    <td>{{ $tradeData->tpPrice }}</td>
                    <td>{{ $tradeData->created_at->diffForHumans() }}</td>
                    <td>{{ $tradeData->updated_at->diffForHumans() }}</td>
                    <td>
                        <div class="col-4 offset-2">
                            <i class="fa-solid fa-xmark" onclick="deleteTradeData('{{ $tradeData->id }}')"></i>
                        </div>
                    </td>
                </tr>
                @endforeach()
            </tbody>
        </table>

        <div class="pagination">
            {{ $tradeDatas->links() }}
        </div>

    </div>
</x-app-layout>


<script>


function deleteAllTradeData() {

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {

        if (result.isConfirmed) {

            $.get('/api/delete/all/trade/data?userId={{ Auth::User()->id }}', function(data) {
                var jsonData = JSON.parse(JSON.stringify(data))

                if (jsonData['status'] == true) {
                    Swal.fire({
                    title: "Deleted!",
                    text: "All Trade Data Have Been Deleted Successfully.",
                    icon: "success"
                    }).then(function () {
                        // Reload Page
                        window.location.reload()
                    })
                }

            })
            
        }
    })

}


function deleteTradeData(tradeDataId) {

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {

        if (result.isConfirmed) {

            $.get('/api/delete/trade/data?tradeDataId=' + tradeDataId, function(data) {
                var jsonData = JSON.parse(JSON.stringify(data))

                if (jsonData['status'] == true) {
                    Swal.fire({
                    title: "Deleted!",
                    text: "Trade Data Have Been Deleted Successfully.",
                    icon: "success"
                    }).then(function () {
                        // Reload Page
                        window.location.reload()
                    })
                }

            })
            
        }
    })

}

</script>