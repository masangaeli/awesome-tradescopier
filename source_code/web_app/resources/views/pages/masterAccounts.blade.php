<x-app-layout>
    <x-slot name="header">

        <div class="row">

            <div class="col-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Master Accounts') }} ({{ $totalMasterAccounts }})
                </h2>
            </div>

            <div class="col-4 offset-4 row">
                <div class="col-md-6">
                    <button class="btn btn-success ">Master Resources</button>
                </div>

                <div class="col-md-6">
                    <button class="btn btn-primary" 
                    data-bs-toggle="modal" data-bs-target="#newMasterAccountModal">Create Master A/C</button>
                </div>
            </div>
        </div>

    </x-slot>

    <div class="py-12 container-fluid">
       
    <div class="container">
        <table class="table table-bordered table-striped table-hovered">
            <thead>
                <tr>
                    <td>S/N</td>
                    <td>Title</td>
                    <td>Info</td>
                    <td>MT Version</td>
                    <td>Token</td>
                    <td>Actions</td>
                </tr>
            </thead>

            <tbody>
                @php $id = 0; @endphp
                @foreach($masterAccounts as $masterAccount)
                <tr>
                    <td>{{ $id += 1 }}</td>
                    <td>{{ $masterAccount->masterTitle }}</td>
                    <td>{{ $masterAccount->masterInfo }}</td>
                    <td>{{ $masterAccount->masterSoftware }}</td>
                    <td>{{ $masterAccount->masterToken }}</td>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <i class="fa-solid fa-pencil"></i>
                            </div>

                            <div class="col-4">
                                <i class="fa-solid fa-xmark" onclick="deleteMasterAccount('{{ $masterAccount->id }}')"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach()
            </tbody>
        </table>

        <div class="pagination">
            {{ $masterAccounts->links() }}
        </div>
    </div>


    <!-- Master Account Modal -->
    <div class="modal fade" id="newMasterAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">New Master Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('postNewMaster') }}" method="POST">
                <table>
                    <tr>
                        <td>Title</td>
                        <td><input type="text" name="title" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        ></td>
                    </tr>

                    <tr>
                        <td>Info</td>
                        <td><input type="text" name="info" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        ></td>
                    </tr>

                    <tr>
                        <td>Master Version</td>
                        <td>
                            <select class="form-control" name="mtVersion">
                                <option value="MT4">Meta Trader 4</option>
                                <option value="MT5">Meta Trader 5</option>
                                <option value="API_SYNC">API Request</option>
                                <option value="TELEGRAM">Telegram Channel</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><input type="hidden" name="_token" value="{{ Session::token() }}"></td>
                        <td><input type="submit" value="Create Master" class="btn btn-primary form-control"></td>
                    </tr>                    
                </table>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    </div>
</x-app-layout>


<script type="text/javascript">

function deleteMasterAccount(masterAccountId) {

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

            $.get('/api/delete/master/account?masterAccountId=' + masterAccountId, function(data) {
                var jsonData = JSON.parse(JSON.stringify(data))

                if (jsonData['status'] == true) {
                    Swal.fire({
                    title: "Deleted!",
                    text: "Master Account Have Been Deleted Successfully.",
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