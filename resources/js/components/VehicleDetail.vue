<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Vehicle Details
                            <button v-if="$role.isPermission('add_vehicle_details')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Vehicle Details</li>
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
                                            <th>Vehicle Name</th>
                                            <th>Vehicle Type</th>
                                            <th>Model</th>
                                            <th>VIN</th>
                                            <th>ECU Name</th>
                                            <th>Image</th>
                                            <th v-if="$role.isAction('update_vehicle_details','delete_vehicle_details')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="vehicledetails.data.length != 0">


<tr v-for="(vehicledetail,index) in vehicledetails.data" :key="vehicledetail.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{vehicledetail.name}}</td>
                                        <td >{{vehicledetail.typename}}</td>
                                        <td >{{vehicledetail.model}}</td>
                                        <td >{{vehicledetail.vin}}</td>
                                        <td >
                                            <div v-for="(func) in vehicledetail.ecu_name" :key="func.id">
                                                <span>{{func.name}}</span>
                                            </div>
                                        </td> 
                                        <td ><img v-bind:src="'..' + vehicledetail.image_path" v-bind:alt="pic" style="    width: 207px;"></td>  
                                        <td>

                                            <a v-if="$role.isPermission('update_vehicle_details')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(vehicledetail)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_vehicle_details')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(vehicledetail.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="vehicledetails.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="vehicledetails" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add Vehicle Details</h5>
                    <h5 class="modal-title" v-show="editmode">Update Vehicle Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateVehicleDetail() : createVehicleDetail()">
                    <div class="modal-body">
                      
                        

                        <div class="form-group">
                            <label>Vehicle Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Vehicle Model</label> <span class="text-danger">*</span>
                            <input v-model="form.model" type="text" name="model"  ref="model" id="model" required @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('model') }">
                            <has-error :form="form" field="model"></has-error>
                        </div>
                        <div class="form-group">
                            <label>VIN</label> <span class="text-danger">*</span>
                            <input v-model="form.vin" type="text" name="vin"  ref="vin" id="vin" required 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('vin') }">
                            <has-error :form="form" field="vin"></has-error>
                        </div>
                        <div class="form-group">
                            <label>ECU</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple"  v-model="form.ecu_id" name="ecu_id[]" id="ecu_id" ref="ecu_id" required >
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in ecu" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.ecu_type">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="ecu_id"></has-error>
                        </div>

                         <div class="form-group">
                            <label>Vehicle Type</label> <span class="text-danger">*</span>
                            <select class="form-control"  v-model="form.vehicle_type_id" name="vehicle_type_id" id="vehicle_type_id" ref="vehicle_type_id" @keydown="getFunDetail()" required>
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in vtype" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.vehicle_type_id">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="vehicle_type_id"></has-error>
                        </div>

                         <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image"  ref="image" id="image" v-on:change="onChange" @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                            <has-error :form="form" field="image"></has-error>
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
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                vehicledetails : {},
                ecu : [],
                vtype : [],
                fromData : "",
                file: '',
                form: new Form({
                    id : '',
                    name: '',
                    model : '',
                    vin : '',
                    ecu_id: [],
                    vehicle_type_id: '',
                    image: '',
                }),
            }
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            getResults(page = 1) {

                   
                  
                  axios.get('/api/vehicledetail?page=' + page).then(({ data }) => (this.vehicledetails = data.data,this.fromData = data.data.from));

                   
            },
            updateVehicleDetail(){
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/vehicledetail/post/'+this.form.id, data, config)
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
                        this.loadVehicleDetail();
                    }, 800);
            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                axios.post('/api/vehicledetail', data, config)
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
                this.form.fill(category);
                 var parseFunction = JSON.parse(category.ecu_id);
                $("#ecu_id").val(parseFunction).trigger('change');
                 $('#image').val('');
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                 $("#ecu_id").val('').trigger('change')
                  $('#image').val('');
                $('#addNew').modal('show');
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
                              this.form.delete('/api/vehicledetail/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadVehicleDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadVehicleDetail(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/vehicledetail").then(({ data }) => (this.vehicledetails = data.data,this.fromData = data.data.from));
                }
            },
            loadEciDetail(){
              axios.get("/api/ecidetail/list").then(({ data }) => (this.ecu = data.data));
          },
          loadVehicleType(){
                axios.get("/api/vehicletype/list").then(({ data }) => (this.vtype = data.data));
          },
            createVehicleDetail(){
                // var countriesData = $('#functionality_id').select2("val");
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var ecudata = $('#ecu_id').val();
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/vehicledetail', data, config)
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
                        this.loadVehicleDetail();
                    }, 800);
            
            },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadVehicleDetail();
            this.loadEciDetail();
            this.loadVehicleType();
             
        }
    }
</script>
