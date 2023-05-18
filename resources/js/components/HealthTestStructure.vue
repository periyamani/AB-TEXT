<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Health Test Structure
                          <a v-if="$role.isPermission('add_vehicle_details')" href="/addhealthteststructure">  <button type="button" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button></a>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Health Test Structure</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('vehicle_details_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>VIN Number</th>
                                            <th>Status</th>
                                            <th v-if="$role.isAction('update_vehicle_details','delete_vehicle_details')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="healthteststructures.data.length != 0">


<tr v-for="(healthteststructure,index) in healthteststructures.data" :key="healthteststructure.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{healthteststructure.name}}</td>
                                        <td >{{healthteststructure.vin_number}}</td>
                                        <td v-if="healthteststructure.active == '1'" style="color:green;">Enabled</td>
                                        <td v-if="healthteststructure.active == '0'" style="color:red;">Disabled</td>
                                        <td style="display:flex !important;">
                                            <a v-if="$role.isPermission('update_vehicle_details')" href="#" class="btn btn-rounded btn-warning-rgba" @click="viewHealthTest(healthteststructure)">
                                                <i class="fa fa-eye blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('update_vehicle_details')" href="#" class="btn btn-rounded btn-primary-rgba" @click="updateHealthTest(healthteststructure.id)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_vehicle_details')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(healthteststructure.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                             &nbsp;
                                             <div v-if="healthteststructure.active == '1'">
                                                <a v-if="$role.isPermission('enable_disable_live_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="enableData(healthteststructure.id,'Disable')">
                                                    Disable
                                                </a>
                                             </div>
                                             <div v-if="healthteststructure.active == '0'">
                                                <a v-if="$role.isPermission('enable_disable_live_data')" href="#" class="btn btn-rounded btn-success-rgba" @click="enableData(healthteststructure.id, 'Enable')">
                                                    Enable
                                                </a>
                                             </div>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="healthteststructures.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="healthteststructures" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
             <div v-else>
                <h6 class="text-danger"> Permission Denied </h6>
            </div>
            <!-- /.card -->
          </div>
        </div>


 <div class="modal fade" id="viewHealthModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Health Test Structure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="">
                    <div class="modal-body">
                        <div class="col-md-12 d-flex p-0">
                        <div class="form-group col-md-6 p-0">
                            <label>Name</label> 
                            <p id="viewName" style="color: #000000d4;"></p>
                        </div>
                        <div class="form-group col-md-6 p-0">
                            <label>VIN Number</label> 
                            <p id="viewVinNumber" style="color: #000000d4;"></p>
                        </div>
                        </div>
                        
                        <h6>Health Test</h6>
                        <hr>
                        <div id="showField" class="row"></div>
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>



    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                healthteststructures : {},
                form: new Form({
                    name: '',
                    vin_number: '',
                    category : [],
                    field : []
                }),
            }
        },
        methods: {
            
            getResults(page = 1) {

                   
                  
                  axios.get('/api/healthteststructure?page=' + page).then(({ data }) => (this.healthteststructures = data.data,this.fromData = data.data.from));

                   
            },
            loadHealthTestStructure(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/healthteststructure").then(({ data }) => (this.healthteststructures = data.data,this.fromData = data.data.from));
                }
            },
            viewHealthTest(data){
                var arr = JSON.parse(data.field);

                const res = Array.from(arr.reduce((m, {category, field}) => 
                    m.set(category, [...(m.get(category) || []), field]), new Map
                ), ([category, field]) => ({category, field})
                );
                $('#viewName').html(data.name);
                $('#viewVinNumber').html(data.vin_number);
                $('#showField').empty();
                if(res.length > 0){
                    for (let i = 0; i < res.length; i++) {
                        $('#showField').append('<div class="col-md-4"><p style="color: #000000d4;font-weight:bolder;">'+res[i].category+'</p><div id="'+i+'"></div><br></div>');
                        if(res[i].field.length > 0){
                            for (let k = 0; k < res[i].field.length; k++) {
                                $('#'+i).append('<div class="row">'+
                                '<div class="col-md-6">'+res[i].field[k]+'</div></div>');                                
                            }
                        }else{
                            $('#'+i).html('---------')
                        }
                    }
                }else{
                    $('#showField').html('No data found');
                }
                $('#viewHealthModal').modal('show');
                console.log(res);
            },
            deleteData(id){
              Swal.fire({
                  title: 'Are you sure?',
                //   text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('/api/healthteststructure/delete/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadHealthTestStructure();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
           enableData(id,dataName){
              Swal.fire({
                  title: 'Are you sure?',
                //   text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, '+dataName+' it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.post('/api/healthteststructure/enableData/'+id+'/'+dataName).then(()=>{
                                      Swal.fire(
                                      dataName+'d !',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadHealthTestStructure();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
          updateHealthTest(id){
              window.location.href = '/updateHealthTestStructure/'+id;
          }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.loadHealthTestStructure();
        },
        }
    
</script>