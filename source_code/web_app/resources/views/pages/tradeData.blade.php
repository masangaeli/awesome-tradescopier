<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trade Data') }}
        </h2>
    </x-slot>

    <div class="py-12 container-fluid">
       
        <table class="table table-bordered">
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
                </tr>
                @endforeach()
            </tbody>
        </table>

        <div class="pagination">
            {{ $tradeDatas->links() }}
        </div>

    </div>
</x-app-layout>
