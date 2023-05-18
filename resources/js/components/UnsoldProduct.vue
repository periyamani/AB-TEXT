<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Unsold Products
                            <button v-if="$role.isPermission('add_product')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Unsold Products</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('unsold_products_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>VCI Name</th>
                                            <th>VCI Number</th>
                                            <th>Technician Name </th>
                                            <th>Sold/Unsold Status</th>
                                            <th>Staus</th>
                                            <th v-if="$role.isMapping('update_product','delete_product','product_mapping_to_user')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="vcidetails.data.length != 0">


<tr v-for="(vcidetail,index) in vcidetails.data" :key="vcidetail.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{vcidetail.name}}</td>
                                        <td >{{vcidetail.vci_number}}</td>
                                        <td >{{vcidetail.technician_name}}</td>
                                        <td v-if="vcidetail.sold_status == '0'"><span class="badge badge-pill badge-danger" style="padding: 0.50em .8em;">Unsold</span></td> <td v-if="vcidetail.sold_status == '1'"><span class="badge badge-pill badge-success" style="padding: 0.50em .8em;">Sold</span></td>
                                        <td v-if="vcidetail.active == '1'"><span class="badge badge-pill badge-success" style="padding: 0.50em .8em;">Active</span></td><td v-if="vcidetail.active == '0'"><span class="badge badge-pill badge-danger" style="padding: 0.50em .8em;">Inactive</span></td>
                                        <td>

                                            <a v-if="$role.isPermission('update_product')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(vcidetail)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_product')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(vcidetail.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                             &nbsp;
                                             <span v-if="$role.isPermission('product_mapping_to_user')">
                                            <a href="#" class="btn btn-rounded btn-warning-rgba" @click="mapping(vcidetail)">
                                                <i class="fa fa-sitemap yellow"></i>
                                            </a>
                                             </span>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="vcidetails.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="vcidetails" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add VCI Details</h5>
                    <h5 class="modal-title" v-show="editmode">Update VCI Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form ref="addform" @submit.prevent="editmode ? updateVciDetail() : createVciDetail()">
                    <div class="modal-body">
                      

                        <div class="form-group">
                            <label>VCI Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>VCI Number</label> <span class="text-danger">*</span>
                            <input v-model="form.vci_number" type="text" name="vci_number"  ref="vci_number" id="vci_number" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('vci_number') }">
                            <has-error :form="form" field="vci_number"></has-error>
                        </div>
                    
                        <div class="form-group" v-show="editmode">
                            <label>Status</label> <span class="text-danger">*</span>

                            <select name="status" id="status" ref="status" v-model="form.status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <has-error :form="form" field="status"></has-error>
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


<div class="modal fade" id="Mapping" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mapping Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addMapping" ref="addMapping" @submit.prevent="mappingCustomer()">
                    <div class="modal-body">
                      
                        <!-- <input v-model="form.mapid" type="hidden" name="mapid"  ref="mapid" id="mapid" class="form-control"> -->
                        <div class="form-group">
                            <label>Customer</label> <span class="text-danger">*</span>
                             <select class="form-control" v-model="form.user_id" name="user_id" ref="user_id" required>
                             <!-- <option value="" selected>Select</option> -->
                              <option 
                                  v-for="(cty,index) in users" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.user_id">{{ cty.name }} - {{ cty.mobile_no }} - {{ cty.email }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
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
                vcidetails : {},
                users : [],
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    mapid: '',
                    active: '',
                    vci_number: '',
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/unsoldProduct?page=' + page).then(({ data }) => (this.vcidetails = data.data,this.fromData = data.data.from));

                   
            },
             mappingCustomer(){
                 
                this.form.post('/api/mapping/'+this.form.id)
                .then((response) => {
                    // success
                    $('#Mapping').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadVciDetail();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            updateVciDetail(){
                 
                this.form.put('/api/vcidetail/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadVciDetail();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/vcidetailCheck/'+countriesData+'/'+name)
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
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            mapping(category){
                this.form.reset();
                $('#Mapping').modal('show');
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
                              this.form.delete('/api/vcidetail/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadVciDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadVciDetail(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/unsoldProduct").then(({ data }) => (this.vcidetails = data.data,this.fromData = data.data.from));
                }
            },
            loadUser(){
              axios.get("/api/userdetail/list").then(({ data }) => (this.users = data.data));
          },
            createVciDetail(){
               this.form.post('/api/vcidetail')
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
                 
                  this.loadVciDetail();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            onClick() {
            //    axios.get("/api/vcidetail")
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

             
            this.loadVciDetail();
            this.loadUser();
             
        }
    }
</script>
