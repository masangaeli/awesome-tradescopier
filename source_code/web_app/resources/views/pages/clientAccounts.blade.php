<x-app-layout>
    <x-slot name="header">
    
    <div class="row">

        <div class="col-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Client Accounts') }} ({{ $totalClients }})
            </h2>
        </div>

        <div class="col-4 offset-4 row">
            <div class="col-md-6">
                <button class="btn btn-success ">Client Resources</button>
            </div>

            <div class="col-md-6">
                <button class="btn btn-primary float-right" 
                data-bs-toggle="modal" data-bs-target="#newClientAccountModal">Create Client A/C</button>
            </div>
        </div>
            
    </div>

    </x-slot>

    <div class="py-12 container">
       
        <table class="table table-bordered table-striped table-hovered">
            <thead>
                <tr>
                    <td>S/N</td>
                    <td>Client Title</td>
                    <td>Client Info</td>
                    <td>Client Comment</td>
                    <td>Client MT Version</td>
                    <td>Client Token</td>
                    <td>Actions</td>
                </tr>
            </thead>


            <tbody>
                @php $id = 0; @endphp
                @foreach($clientAccounts as $clientAccount)
                <tr>
                    <td>{{ $id += 1 }}</td>
                    <td>{{ $clientAccount->clientTitle }}</td>
                    <td>{{ $clientAccount->clientInfo }}</td>
                    <td>{{ $clientAccount->clientTradeComment }}</td>
                    <td>{{ $clientAccount->clientSoftware }}</td>
                    <td>{{ $clientAccount->clientToken }}</td>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <i class="fa-solid fa-link" 
                                onclick="addMasterPrepare('{{ $clientAccount->id }}', '{{ $clientAccount->clientTitle }}')"></i>
                            </div>

                            <div class="col-4">
                                <i class="fa-solid fa-pencil" onclick="updateClient('{{ $clientAccount->id }}', '{{ $clientAccount->clientSoftware }}', '{{ $clientAccount->clientTradeComment }}', '{{ $clientAccount->clientInfo }}', '{{ $clientAccount->clientTitle }}')"></i>
                            </div>

                            <div class="col-4">
                                <i class="fa-solid fa-xmark" onclick="deleteClient('{{ $clientAccount->id }}')"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $clientAccounts->links() }}
        </div>

        
        
        <!-- Add New Master to Client Modal -->
        <div class="modal fade" id="addNewMasterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageMastersModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="addNewMasterList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>



         <!-- Update Client Account Modal -->
         <div class="modal fade" id="updateClientAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Client Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            <form action="{{ route('postUpdateClient') }}" method="POST">
                <table>
                    <tr>
                        <td>Title <span class="color-red">*</span></td>
                        <td>
                            <input type="text" id="edit_title" 
                                   name="title" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Client Title" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Info</td>   
                        <td>
                            <input type="text" id="edit_info" 
                                name="info" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Client Info" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Trades Comment <span class="color-red">*</span></td>
                        <td>
                            <input type="text" id="edit_clientTradeComment" 
                                name="clientTradeComment" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Trades Comment" required />
                        </td>
                    </tr>

                    <tr>
                        <td>MT Version <span class="color-red">*</span></td>
                        <td>
                            <select id="edit_mtVersion" name="mtVersion" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="MT4">Meta Trader 4</option>
                                <option value="MT5">Meta Trader 5</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="hidden" name="clientId" id="edit_clientId" />
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                        </td>
                        <td><input type="submit" value="Update Client" class="btn btn-primary form-control"></td>
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

       

        <!-- New Client Account Modal -->
        <div class="modal fade" id="newClientAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Client Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            <form action="{{ route('postNewClient') }}" method="POST">
                <table>
                    <tr>
                        <td>Title <span class="color-red">*</span></td>
                        <td>
                            <input type="text" id="title" 
                                   name="title" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Client Title" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Info</td>   
                        <td>
                            <input type="text" id="info" 
                                name="info" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Client Info" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Trades Comment <span class="color-red">*</span></td>
                        <td>
                            <input type="text" id="clientTradeComment" 
                                name="clientTradeComment" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Trades Comment" required />
                        </td>
                    </tr>

                    <tr>
                        <td>MT Version <span class="color-red">*</span></td>
                        <td>
                            <select id="mtVersion" name="mtVersion" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="MT4">Meta Trader 4</option>
                                <option value="MT5">Meta Trader 5</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><input type="hidden" name="_token" value="{{ Session::token() }}"></td>
                        <td><input type="submit" value="Create Client" class="btn btn-primary form-control"></td>
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


<script type="text/javascript">

function updateClient(clientId, clientSoftware, clientTradesComment, clientInfo, clientTitle) {

    $("#updateClientAccountModal").modal('show')

    $("#edit_clientId").val(clientId);

    $("#edit_mtVersion").val(clientSoftware)
    $("#edit_clientTradeComment").val(clientTradesComment)
    $("#edit_info").val(clientInfo)
    $("#edit_title").val(clientTitle)

}

function deleteClient(clientAccountId) {

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

            $.get('/api/delete/client/account?clientAccountId=' + clientAccountId, function(data) {
                var jsonData = JSON.parse(JSON.stringify(data))

                if (jsonData['status'] == true) {
                    Swal.fire({
                    title: "Deleted!",
                    text: "Client Account Have Been Deleted Successfully.",
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

function addMasterPrepare(clientAccountId, clientAccountTitle) {

    $("#manageMastersModalTitle").html("Manage Master to Client ("+clientAccountTitle+")");

    $("#addNewMasterModal").modal('show');

    $.get('/api/pull/client/not/added/masters?clientAccountId='+clientAccountId+'&userId={{ Auth::User()->id }}', function(data) {
        var jsonData = JSON.parse(JSON.stringify(data));

        var mastersList = jsonData['mastersList'];

        var mastersListAdded = jsonData['mastersListAdded']


        $("#addNewMasterList").html(`
        
        <strong>
            <h3>Masters List to Add (`+mastersList.length+`)</h3>
        </strong>
        <br/><hr/><br/>

        <table>

        <tbody>

        `);


        // Masters List to Add
        mastersList.forEach(function (masterData) {
           
            $("#addNewMasterList").append(`
            
            <form action="/postNewClientMasterConnection" method="POST">

            <div class="row">
                <div class="col-3">`+ masterData.masterTitle  +`</div>
                                
                <div class="col-3">
                    <input type="text" 
                           name="symbolKeyword" 
                           id="symbolKeyword" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                           placeholder="Keyword Eg. .r / .m" 
                           class="form-control" />
                </div>

                <div class="col-3">
                    <input type="decimal" 
                        name="lotSize" 
                        placeholder="Lot Size..." 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        />
                </div>
                
                <div class="col-3">
                    <input type="hidden" name="_token" value="{{ Session::token() }}"/>
                    <input type="hidden" name="clientAccountId" value="`+clientAccountId+`"/>
                    <input type="hidden" name="masterAccountId" value="`+masterData['id']+`"/>
                    <button class="btn btn-primary form-control">Add Master</button>
                </div>
            </div>

            </form>

            <br/><br/>
            
            `);

        })


        $("#addNewMasterList").append(`

        </tbody>

        </table>


        <strong>
            <h3>Masters List to Added (`+mastersListAdded.length+`)</h3>
        </strong>
        <br/><hr/><br/>

        <table>

        <tbody>

        `);

        mastersListAdded.forEach(function (masterDataA) {
           
           $("#addNewMasterList").append(`
           

           <div class="row">
               <div class="col-3">`+ masterDataA.masterTitle  +`</div>
                               
               <div class="col-3">
                   <input type="text" 
                          name="symbolKeyword" 
                          id="symbolKeyword" 
                          value="`+masterDataA.symbolKeyword+`"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                          placeholder="Keyword Eg. .r / .m" 
                          class="form-control" />
               </div>

               <div class="col-3">
                   <input type="decimal" 
                       name="lotSize" 
                       value="`+masterDataA.lotSize+`"
                       placeholder="Lot Size..." 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                       />
               </div>
               
               <div class="col-3">
                  
                   
                <form action="/update/post/client/master/connection" method="POST">
                   <input type="hidden" name="clientAccountId" value="`+clientAccountId+`"/>
                   <input type="hidden" name="masterAccountId" value="`+masterDataA['id']+`"/>
                   <input type="hidden" name="_token" value="{{ Session::token() }}"/>
                   <input type="submit" class="btn btn-success form-control" value="Update"/>
                </form>
            

               <form action="/delete/post/client/master/connection" method="POST">
                   <input type="hidden" name="clientAccountId" value="`+clientAccountId+`"/>
                   <input type="hidden" name="masterAccountId" value="`+masterDataA['id']+`"/>
                   <input type="hidden" name="_token" value="{{ Session::token() }}"/>
                   <input type="submit" class="btn btn-danger form-control" value="Delete"/>
                </form>
            
               </div>
           </div>


           <br/><br/>
           
           `);

       });


        $("#addNewMasterList").append(`

        </tbody>

        </table>`);
            
    })

}

</script>

</x-app-layout>
