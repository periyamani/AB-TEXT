<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Users
                            <button v-if="$role.isPermission('add_users')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('users_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Role</th>
                                            <th v-if="$role.isAction('update_users','delete_users')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="employeedetails.data.length != 0">


<tr v-for="(employeedetail,index) in employeedetails.data" :key="employeedetail.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{employeedetail.name}}</td>
                                        <td >{{employeedetail.mobile_no}}</td>
                                        <td >{{employeedetail.email}}</td>
                                        <td >{{employeedetail.address1}},{{employeedetail.address2}},{{employeedetail.city}},{{employeedetail.state}}</td>
                                        <td >{{employeedetail.role_name}}</td>
                                        <td>

                                            <a v-if="$role.isPermission('update_users')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(employeedetail)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_users')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(employeedetail.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="employeedetails.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="employeedetails" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add Users</h5>
                    <h5 class="modal-title" v-show="editmode">Update Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createEmployee"> -->

                <form ref="addform" @submit.prevent="editmode ? updateEmployeeDetail() : createEmployeeDetail()">
                    <div class="modal-body">
                       <div class="row">
                        <div class="form-group col-md-6">
                            <label>Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Mobile</label> <span class="text-danger">*</span>
                            <input v-model="form.mobile_no" type="text" name="mobile_no"  ref="mobile_no" id="mobile_no" required
                             pattern="[0-9]+" title="Please enter numbers only" maxlength="10"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('mobile_no') }">
                            <has-error :form="form" field="mobile_no"></has-error>
                        </div>
                       </div>
                       <div class="row">
                        <div class="form-group col-md-6">
                            <label>Email</label> <span class="text-danger">*</span>
                            <input v-model="form.email" type="email" name="email"  ref="email" id="email" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Address1</label> <span class="text-danger">*</span>
                            <input v-model="form.address1" type="text" name="address1"  ref="address1" id="address1" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address1') }">
                            <has-error :form="form" field="address1"></has-error>
                        </div>
                       </div>

                       <div class="row">
                        <div class="form-group col-md-6">
                            <label>Address2</label>
                            <input v-model="form.address2" type="text" name="address2"  ref="address2" id="address2"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address2') }">
                            <has-error :form="form" field="address2"></has-error>
                        </div>

                         <div class="form-group col-md-6">
                            <label>City</label> <span class="text-danger">*</span>
                            <input v-model="form.city" type="text" name="city"  ref="city" id="city" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('city') }">
                            <has-error :form="form" field="address2"></has-error>
                        </div>
                       </div>
                     <div class="row">
                         <div class="form-group col-md-6">
                            <label>State</label> <span class="text-danger">*</span>
                            <input v-model="form.state" type="text" name="state"  ref="state" id="state" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('state') }">
                            <has-error :form="form" field="address2"></has-error>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Password</label> <span class="text-danger">*</span>
                            <input v-model="form.password" type="text" name="password"  ref="password" id="password"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div> 
                     </div>  
                     <div class="row">
                         <div class="form-group col-md-6">
                           <label>Role</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.role" name="role" ref="role" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in roles" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.role">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="role"></has-error>
                        </div>
                     </div>   

                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button  v-show="editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Update</button>
                        <button  v-show="!editmode" :disabled="isSending" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
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
                employeedetails : {},
                 roles : [],
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    email: '',
                    mobile_no: '',
                    address1: '',
                    address2: '',
                    city: '',
                    state: '',
                    password: '',
                    role : ''
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/employeedetail?page=' + page).then(({ data }) => (this.employeedetails = data.data,this.fromData = data.data.from));

                   
            },
            updateEmployeeDetail(){
                 
                this.form.put('/api/employeedetail/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadEmployeeDetail();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/employeedetailCheck/'+countriesData+'/'+name)
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
                $("#password").removeAttr("required");
                this.form.fill(category);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $("#password").attr("required", "true");
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
                              this.form.delete('/api/employeedetail/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadEmployeeDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadEmployeeDetail(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/employeedetail").then(({ data }) => (this.employeedetails = data.data,this.fromData = data.data.from));
                }
            },
            loadRole(){
              axios.get("/api/role/list").then(({ data }) => (this.roles = data.data));
          },
            createEmployeeDetail(){
               this.form.post('/api/employeedetail')
              .then((response)=>{
                  if(response.data.message != "Email ID Already exist"){
                        $('#addNew').modal('hide');
                         Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });
                  }else{
                       Toast.fire({
                        icon: 'error',
                        title: response.data.message
                  });
                  }
                 
                  this.loadEmployeeDetail();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            onClick() {
            //    axios.get("/api/employeedetail")
            //     .then((response) => {
            //          var fileURL = window.URL.createObjectURL(new Blob([response.data.data.data]));
            //          var fileLink = document.createElement('a');
   
            //          fileLink.href = "Mohanraj";
            //          fileLink.setAttribute('download', 'file.pdf');
            //          document.body.appendChild(fileLink);
   
            //          fileLink.click();
            //     });
    //         var pdf = new jsPDF();
    // var element = document.getElementById('facture');
    // var width= element.style.width;
    // var height = element.style.height;
    // html2canvas(element).then(canvas => {
    //     var image = canvas.toDataURL('image/png');
    //     pdf.addImage(image, 'JPEG', 15, 40, width, height);
    //     pdf.save('facture' + moment(this.facture.date_debut).format('LL') + '_' + moment(this.facture.date_fin).format('LL') + '.pdf');
    // });
          }
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadEmployeeDetail();
            this.loadRole();
             
        }
    }
</script>
