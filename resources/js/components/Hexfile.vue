<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Hex File
                            <button v-if="$role.isPermission('add_hex_file')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Hex File</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('hex_file_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table" style="width:200%">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>Vehicle</th>
                                            <th>Ecu</th>
                                            <th>File name</th>
                                            <th>File Size</th>
                                            <th>Remark</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Approved By</th>
                                            <th>Approver Remark</th>
                                            <th v-if="$role.isAction('update_hex_file','delete_hex_file','approve_hex_file')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="hexfiles.data.length != 0">


<tr v-for="(hexfile,index) in hexfiles.data" :key="hexfile.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{hexfile.name}}</td>
                                        <td >{{hexfile.vehicle_name}}</td>
                                        <td >{{hexfile.ecu_name}}</td>
                                        <td >{{hexfile.hex_file}}</td>
                                        <td >{{hexfile.file_size}}</td>
                                        <td >{{hexfile.remark}}</td>
                                        <td v-if="hexfile.file_status == 'Pending'"><span class="badge badge-pill badge-warning">{{hexfile.file_status}}</span></td>
                                        <td v-else-if="hexfile.file_status == 'Rejected'"><span class="badge badge-pill badge-danger">{{hexfile.file_status}}</span></td>
                                        <td v-else><span class="badge badge-pill badge-success">{{hexfile.file_status}}</span></td>
                                        <td >{{hexfile.technician_name}}</td>
                                        <td >{{hexfile.approver_name}}</td>
                                        <td >{{hexfile.approver_remark}}</td>
                                        <td>

                                            <a v-if="$role.isPermission('update_hex_file')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(hexfile)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_hex_file')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(hexfile.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('approve_hex_file')" href="#" class="btn btn-rounded btn-warning-rgba" @click="approveData(hexfile)">
                                                <i class="fa fa-thumbs-up yellow"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="hexfiles.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="hexfiles" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <div v-else>
                <h6 class="text-danger"> Permission Denied </h6>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Add Hex File</h5>
                    <h5 class="modal-title" v-show="editmode">Update Hex File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateHexFile() : createHexFile()">
                    <div class="modal-body">
                      
                        

                         <div class="form-group">
                            <label>Vehicle</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.vehicle_id" name="vehicle_id" ref="vehicle_id" id="vehicle_id" required v-on:change="getTextData">
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in vehicles" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.vehicle_id">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label>ECU</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.ecu_id" name="ecu_id" id="ecu_id" ref="ecu_id" required v-on:change="getTextData">
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in ecu" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.ecu_type">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="ecu_id"></has-error>
                        </div>
                         <div class="form-group">
                            <label>Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required readonly
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Remark</label> <span class="text-danger">*</span>
                            <textarea type="text" name="remark"  ref="remark" id="remark" cols="10" rows="3"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('remark') }"></textarea>
                            <has-error :form="form" field="remark"></has-error>
                        </div>
                         <div class="form-group">
                            <label>Hex File</label>
                            <input type="file" accept=".hex" name="hex_file"  ref="hex_file" id="hex_file"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('hex_file') }" required>
                            <has-error :form="form" field="hex_file"></has-error>
                        </div>

                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button  v-show="editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Update</button>
                        <button  v-show="!editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>

    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Hex File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addApprove" @submit.prevent="approveHexFile()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.file_status" name="file_status" id="file_status" ref="file_status" required v-on:change="getTextData">
                             <!-- <option value="">Select</option> -->
                              <option value="Pending">Pending</option>
                              <option value="Approved">Approved</option>
                              <option value="Rejected">Rejected</option>
                            </select>
                            <has-error :form="form" field="file_status"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Remark</label> <span class="text-danger">*</span>
                            <textarea type="text" v-model="form.approver_remark" name="approver_remark"  ref="approver_remark" id="approver_remark" cols="10" rows="3"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('approver_remark') }"></textarea>
                            <has-error :form="form" field="approver_remark"></has-error>
                        </div>
                       
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
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
                editmode: false,
                hexfiles : {},
                vehicles : [],
                 ecu : [],
                fromData : "",
                file: '',
                form: new Form({
                    id : '',
                    name: '',
                    hex_file: '',
                    vehicle_id: '',
                    ecu_id: '',
                    remark: '',
                    approver_remark : '',
                    file_status : '',
                }),
            }
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/hexfile?page=' + page).then(({ data }) => (this.hexfiles = data.data,this.fromData = data.data.from));

                  this.$Progress.finish();
            },
            updateHexFile(){
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/hexfile/update/'+this.form.id, data, config)
                .then(function (response) {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                    setTimeout(() => {
                        this.loadHexFile();
                    }, 800);
            },
            approveHexFile(){
                this.form.put('/api/hexfile/approve/'+this.form.id)
                .then((response) =>  {
                    // success
                    $('#approveModal').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                    setTimeout(() => {
                        this.loadHexFile();
                    }, 800);
            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                axios.post('/api/hexfile', data, config)
                .then((response) => {
                    if(response.data.data){
                      Toast.fire({
                      icon: 'error',
                      title: response.data.message
                    });
                    
                    }
                })
                } 
            },
            editModal(category){
                
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                $('#hex_file').val('');
                 $("#hex_file").removeAttr("required");
                this.form.fill(category);
                $("#remark").val(category.remark);
                
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $("#remark").val("");
                $('#hex_file').val('');
                $("#hex_file").attr("required", "true");
                $('#addNew').modal('show');
            },
            approveData(category){
                this.form.reset();
                $("#approver_remark").val("");
                $('#approveModal').modal('show');
                this.form.fill(category);
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
                              this.form.delete('/api/hexfile/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadHexFile();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadHexFile(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/hexfile").then(({ data }) => (this.hexfiles = data.data,this.fromData = data.data.from));
                }
            },
            loadVehicle(){
              axios.get("/api/vehicledetail/list").then(({ data }) => (this.vehicles = data.data));
          },
           loadEciDetail(){
              axios.get("/api/ecidetail/list").then(({ data }) => (this.ecu = data.data));
          },
            createHexFile(){
                // var countriesData = $('#functionality_id').select2("val");
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/hexfile', data, config)
                    .then(function (response) {
                        if(response.data.message != "Already exist"){
                        $('#addNew').modal('hide');
                        // $("#ecu_id").val('').trigger('change');
                            Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        }else{
                            //  $("#ecu_id").val(ecudata).trigger('change')
                            Toast.fire({
                                icon: 'error',
                                title: response.data.message
                        });
                        }
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                    setTimeout(() => {
                        this.loadHexFile();
                    }, 800);
            
            },
            getTextData(){
                var vechile = $('#vehicle_id :selected').text();
                var ecu = $('#ecu_id :selected').text();
                this.form.name = vechile+'_'+ecu;
            },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadHexFile();
            this.loadEciDetail();
            this.loadVehicle();
            this.$Progress.finish();
        }
    }
</script>
