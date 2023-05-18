<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Dealers
                            <button v-if="$role.isPermission('add_customer')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    
                  
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dealers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('customer_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Ledger_Name</th>
                                            <th>Account_Name</th>
                                            <th>Name</th>
                                            <th>ledgerRole</th>
                                            <th>Mobile/Tel No</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Short_Code</th>
                                            <th>Firm</th>
                                            <th>Country</th>
                                            <th>Pin</th>
                                            <th>Stock.A/c</th>
                                            <th>Transport</th>
                                            <th>Booking_Place</th>
                                            <th>Through</th>
                                            <th>L.L.Name</th>
                                            <th>Line</th>
                                            <th>ledgerStatus</th>
                                            <th v-if="$role.isAction('update_customer','delete_customer')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="userdetails.data.length != 0">


<tr v-for="(userdetail,index) in userdetails.data" :key="userdetail.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{userdetail.name}}</td>
                                        <td >{{userdetail.accName}}</td>
                                        <td >{{userdetail.name2}}</td>
                                        <td >{{userdetail.ledgerRole}}</td>
                                        <td >{{userdetail.mobile_no}}<br>{{userdetail.telNo}}</td>
                                        <td >{{userdetail.email}}</td>
                                        <td >{{userdetail.address1}},{{userdetail.area}},{{userdetail.city}},{{userdetail.state}},{{userdetail.address2}}</td>
                                        <td >{{userdetail.shortCode}}</td>
                                        <td >{{userdetail.firm}}</td>
                                        <td >{{userdetail.country}}</td>
                                        <td >{{userdetail.pin}}</td>
                                        <td >{{userdetail.stockAC}}</td>
                                        <td >{{userdetail.transport}}</td>
                                        <td >{{userdetail.bookingPlace}}</td>
                                        <td >{{userdetail.through}}</td>
                                        <td >{{userdetail.llName}}</td>
                                        <td >{{userdetail.line}}</td>
                                        <td >{{userdetail.ledgerStatus}}</td>
                                        <td>

                                            <a v-if="$role.isPermission('update_customer')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(userdetail)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_customer')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(userdetail.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                            &nbsp;
                                            
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="userdetails.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="userdetails" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add Ledger</h5>
                    <h5 class="modal-title" v-show="editmode">Update Ledger</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form ref="addform" @submit.prevent="editmode ? updateUserDetail() : createUserDetail()">
                    <div class="modal-body">
                       <div class="row">
                        <div class="form-group col-md-3">
                            <label>Ledger Name</label> 
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name"  
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Account Name</label> 
                            <input v-model="form.accName" type="text" name="accName"  ref="accName" id="accName"  
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('accName') }">
                            <has-error :form="form" field="accName"></has-error>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label>Short Code</label> 
                            <input v-model="form.shortCode" type="text" name="shortCode"  ref="shortCode" id="shortCode" 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('shortCode') }">
                            <has-error :form="form" field="shortCode"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Firm</label> 
                            <input v-model="form.firm" type="text" name="firm"  ref="firm" id="firm" 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('firm') }">
                            <has-error :form="form" field="firm"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                             <label>Is Active?</label> <span class="text-danger">*</span>
                             <select v-model="form.ledgerStatus" ref="ledgerStatus" id="ledgerStatus" required name="ledgerStatus" @change="onChange($event)" class="form-select form-control" :class="{ 'is-invalid': form.errors.has('ledgerStatus') }">
                                
                                <option value="Active">Active</option>
                                <option value="In Active">In Active</option>
                                
                            </select>
                            <has-error :form="form.ledgerStatus" field="ledgerStatus"></has-error>
                         </div>
                       </div>
                       <br>
                      
                       <div class="row">
                        <div class="form-group col-md-2">
                            <label>Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name2" type="text" name="name2"  ref="name2" id="name2" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name2') }">
                            <has-error :form="form" field="name2"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Mobile</label> <span class="text-danger">*</span>
                            <input v-model="form.mobile_no" type="text" name="mobile_no"  ref="mobile_no" id="mobile_no" required
                             pattern="[0-9]+" title="Please enter numbers only" maxlength="10"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('mobile_no') }">
                            <has-error :form="form" field="mobile_no"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Tel No</label> <span class="text-danger">*</span>
                            <input v-model="form.telNo" type="text" name="telNo"  ref="telNo" id="telNo" required
                             pattern="[0-9]+" title="Please enter numbers only" maxlength="10"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('telNo') }">
                            <has-error :form="form" field="telNo"></has-error>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Email</label> 
                            <input v-model="form.email" type="email" name="email"  ref="email" id="email" 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Street</label> <span class="text-danger">*</span>
                            <input v-model="form.address1" type="text" name="address1"  ref="address1" id="address1" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address1') }">
                            <has-error :form="form" field="address1"></has-error>
                        </div>
                        
                       </div>
                       <div class="row">
                        
                        <div class="form-group col-md-2">
                            <label>Area</label> <span class="text-danger">*</span>
                            <input v-model="form.area" type="text" name="area"  ref="area" id="area" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('area') }">
                            <has-error :form="form" field="area"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>City</label> <span class="text-danger">*</span>
                            <input v-model="form.city" type="text" name="city"  ref="city" id="city" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('city') }">
                            <has-error :form="form" field="city"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>State</label> <span class="text-danger">*</span>
                            <input v-model="form.state" type="text" name="state"  ref="state" id="state" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('state') }">
                            <has-error :form="form" field="state"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>District</label>
                            <input v-model="form.address2" type="text" name="address2"  ref="address2" id="address2"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address2') }">
                            <has-error :form="form" field="address2"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Country</label> <span class="text-danger">*</span>
                            <input v-model="form.country" type="text" name="country"  ref="country" id="country" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                            <has-error :form="form" field="country"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Pin Code</label>
                            <input v-model="form.pin" type="text" name="pin"  ref="pin" id="pin" 
                             pattern="[0-9]+" title="Please enter numbers only" 
                                class="form-control" :class="{ 'is-invalid': form.errors.has('pin') }">
                            <has-error :form="form" field="pin"></has-error>
                        </div>
                       </div>
                       
                       <div class="row">
                        
                        <div class="form-group col-md-2">
                             <label>Is Cheque Stock A/c?</label>
                             <select v-model="form.stockAC" ref="stockAC" id="stockAC"  name="stockAC" @change="onChange($event)" class="form-select form-control" :class="{ 'is-invalid': form.errors.has('stockAC') }">
                                
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                
                            </select>
                            <has-error :form="form.stockAC" field="stockAC"></has-error>
                         </div>
                       </div> 
                       
                     <br>
                     <div class="row">
                         
                        
                        <div class="form-group col-md-2">
                            <label>Transport</label> <span class="text-danger">*</span>
                            <input v-model="form.transport" type="text" name="transport"  ref="transport" id="transport" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('transport') }">
                            <has-error :form="form" field="transport"></has-error>
                        </div>
                        <div class="form-group col-md-2">
                            <label>BookingPlace</label>
                            <input v-model="form.bookingPlace" type="text" name="bookingPlace"  ref="bookingPlace" id="bookingPlace" 
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('bookingPlace') }">
                            <has-error :form="form" field="bookingPlace"></has-error>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Through</label> <span class="text-danger">*</span>
                            <input v-model="form.through" type="text" name="through"  ref="through" id="through" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('through') }">
                            <has-error :form="form" field="through"></has-error>
                        </div> 
                        <div class="form-group col-md-2">
                             <label>L.L Name</label> 
                             <input v-model="form.llName" type="text" name="llName"  ref="llName" id="llName" 
                              
                                 class="form-control" :class="{ 'is-invalid': form.errors.has('llName') }">
                             <has-error :form="form" field="llName"></has-error>
                         </div>
                         <div class="form-group col-md-3">
                             <label>Line</label>
                             <input v-model="form.line" type="text" name="line"  ref="line" id="line" 
                              
                                 class="form-control" :class="{ 'is-invalid': form.errors.has('line') }">
                             <has-error :form="form" field="line"></has-error>
                         </div>
                     </div>   
                     
                     <div class="row">
                         
                        
                        
                        
                         
                         
                         <div class="form-group col-md-3">
                             <label>Role</label> <span class="text-danger">*</span>
                             <select v-model="form.ledgerRole" ref="ledgerRole" id="ledgerRole" required name="ledgerRole" @change="onChange($event)" class="form-select form-control" :class="{ 'is-invalid': form.errors.has('ledgerRole') }">
                                
                                <option value="Weaver">Weaver</option>
                                <option value="Warper">Warper</option>
                                <option value="Roller">Roller</option>
                                <option value="Dyer">Dyer</option>
                                <option value="Employee">Employee / Agent</option>
                                <option value="Processor">Processor</option>
                                <option value="Job Worker">Job Worker</option>
                                <option value="Winder">Winder</option>
                            </select>
                            <has-error :form="form.ledgerRole" field="ledgerRole"></has-error>
                         </div>
                         
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
                userdetails : {},
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    accName: '',
                    email: '',
                    mobile_no: '',
                    telNo: '',
                    address1: '',
                    address2: '',
                    city: '',
                    state: '',
                    shortCode: '',
                    firm: '',
                    name2: '',
                    country: '',
                    pin: '',
                    stockAC: '',
                    transport: '',
                    bookingPlace: '',
                    through: '',
                    llName: '',
                    line: '',
                    ledgerRole: '',
                    ledgerStatus: '',
                    area: '',
    
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/userdetail?page=' + page).then(({ data }) => (this.userdetails = data.data,this.fromData = data.data.from));

                   
            },
            updateUserDetail(){
                 
                this.form.put('/api/userdetail/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadUserDetail();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/userdetailCheck/'+countriesData+'/'+name)
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
                              this.form.delete('/api/userdetail/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadUserDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
          approveData(id){
              Swal.fire({
                  title: 'Are you sure?',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, Approve it!'
                  }).then((result) => {
                        if (result.value) {
                              this.form.post('/api/userdetail/approve/'+id).then(()=>{
                                      Swal.fire(
                                      'Approved!',
                                      'Your file has been Approved.',
                                      'success'
                                      );
                                  this.loadUserDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
           unapproveData(id){
              Swal.fire({
                  title: 'Are you sure?',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, Unapprove it!'
                  }).then((result) => {
                        if (result.value) {
                              this.form.post('/api/userdetail/unapprove/'+id).then(()=>{
                                      Swal.fire(
                                      'Approved!',
                                      'Your file has been Unapproved.',
                                      'success'
                                      );
                                  this.loadUserDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadUserDetail(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/userdetail").then(({ data }) => (this.userdetails = data.data,this.fromData = data.data.from));
                }
            },
            createUserDetail(){
               this.form.post('/api/adduserdetail')
              .then((response)=>{
                  if(response.data.message != "Already exist"){
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
                 
                  this.loadUserDetail();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            onClick() {
            //    axios.get("/api/userdetail")
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

             
            this.loadUserDetail();
             
        }
        
    }
    function yesnoCheck(that) {
    if (that.value == "other") {
  alert("check");
        document.getElementById("ifYes").style.display = "block";
    } else {
        document.getElementById("ifYes").style.display = "none";
    }
}
</script>
