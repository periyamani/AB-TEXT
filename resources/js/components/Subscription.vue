<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Subscription List
                            <button v-if="$role.isPermission('add_subscription')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Subscription List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('subscription_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Customer Name</th>
                                            <th>Customer Mobile</th>
                                            <th>VCI Name</th>
                                            <th>VCI Number</th>
                                            <th>Amount</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Payment Status</th>
                                            <th v-if="$role.isAction('update_subscription','delete_subscription')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="subscriptionDatas.data.length != 0">


<tr v-for="(subscriptionData,index) in subscriptionDatas.data" :key="subscriptionData.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{subscriptionData.custname}}</td>
                                        <td >{{subscriptionData.custmobile}}</td>
                                        <td >{{subscriptionData.vciname}}</td>
                                        <td >{{subscriptionData.vcinumber}}</td>
                                        <td >{{subscriptionData.amount}}</td>
                                        <td >{{subscriptionData.start_date}} - {{subscriptionData.end_date}}</td>
                                        <td v-if="subscriptionData.active == '1'"><span class="badge badge-pill badge-success">Active</span></td>
                                        <td v-else><span class="badge badge-pill badge-danger">Expired</span></td>
                                        <td v-if="subscriptionData.payment_status == 'Completed'"><span class="badge badge-pill badge-success">{{subscriptionData.payment_status}}</span></td>
                                        <td v-else><span class="badge badge-pill badge-danger">{{subscriptionData.payment_status}}</span></td>
                                        <td>

                                            <a v-if="$role.isPermission('update_subscription')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(subscriptionData)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_subscription')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(subscriptionData.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="subscriptionDatas.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="subscriptionDatas" @pagination-change-page="getResults"></pagination>
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

                <form ref="addform" @submit.prevent="editmode ? updateSubscription() : createSubscription()">
                    <div class="modal-body">
                      
                        <div class="form-group">
                            <label>Customer</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.user_id" name="user_id" ref="user_id" v-on:change="getvcidetails" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in users" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.user_id">{{ cty.name }} - {{ cty.mobile_no }} - {{ cty.email }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
                        </div>

                         <div class="form-group">
                            <label>VCI</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.vci_id" name="vci_id" ref="vci_id" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in vcis" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.user_id">{{ cty.name }} - {{ cty.vci_number }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
                        </div>

                         <div class="form-group">
                            <label>Plan</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.subscription_plan_id" name="subscription_plan_id" ref="subscription_plan_id" v-on:change="getplandetails" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in plans" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.user_id">{{ cty.name }} </option>
                            </select>
                            <has-error :form="form" field="subscription_plan_id"></has-error>
                        </div>


                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Amount</label>
                            <input v-model="form.amount" type="text" name="amount"  ref="amount" id="amount" required readonly
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                            <has-error :form="form" field="amount"></has-error>
                        </div>
                         <div class="form-group col-md-6">
                            <label>Duration</label>
                            <input v-model="form.duration" type="text" name="duration"  ref="duration" id="duration" required readonly
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('duration') }">
                            <has-error :form="form" field="duration"></has-error>
                        </div>
                        </div>
                       
                       <div class="form-group">
                            <label>Payment Mode</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.payment_mode_id" name="payment_mode_id" ref="payment_mode_id" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in modes" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.user_id">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="payment_mode_id"></has-error>
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
                subscriptionDatas : {},
                users : [],
                vcis : [],
                plans : [],
                modes : [],
                fromData : "",
                form: new Form({
                    id : '',
                    user_id: '',
                    vci_id: '',
                    payment_mode_id: '',
                    subscription_plan_id: '',
                    start_date: '',
                    end_date: '',
                    duration: '',
                    amount: '',
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/subscriptionData?page=' + page).then(({ data }) => (this.subscriptionDatas = data.data,this.fromData = data.data.from));

                   
            },
            updateSubscription(){
                 
                this.form.put('/api/subscriptionData/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadSubscription();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/subscriptionDataCheck/'+countriesData+'/'+name)
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
             getvcidetails(){
                var id = this.$refs.user_id.value;
                if(id != ''){
                    axios.get("/api/getUserVciDetails/"+id).then(({ data }) => (this.vcis = data.data));
                } 
            },
             
              getplandetails(){
                var id = this.$refs.subscription_plan_id.value;
                if(id != ''){
                    axios.get("/api/getPlanDetails/"+id).then(({ data }) => (this.form.amount = data.data.amount,this.form.duration = data.data.duration+' Month'));
                } 
            },
            editModal(category){
                
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.getupdatevcidetails(category.vci_id,category.user_id);
                this.form.fill(category);
                this.form.duration = category.duration+' Month';
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            getupdatevcidetails(vci,user){
                if(user != ''){
                    axios.get("/api/getUserVciDetails/"+user).then(({ data }) => (this.vcis = data.data,this.form.vci_id = vci));
                } 
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
                              this.form.delete('/api/subscriptionData/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadSubscription();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadSubscription(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/subscriptionData").then(({ data }) => (this.subscriptionDatas = data.data,this.fromData = data.data.from));
                }
            },
            loadUser(){
              axios.get("/api/userdetail/list").then(({ data }) => (this.users = data.data));
          },
          loadVci(data){
              axios.get("/api/vcidetail/list").then(({ data }) => (this.vcis = data.data));
          },
          loadPlan(){
              axios.get("/api/subscription/list").then(({ data }) => (this.plans = data.data));
          },
           loadMode(){
              axios.get("/api/paymentmode/list").then(({ data }) => (this.modes = data.data));
          },
            createSubscription(){
               this.form.post('/api/subscriptionData')
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
                 
                  this.loadSubscription();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            onClick() {
            //    axios.get("/api/subscriptionData")
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

             
            this.loadSubscription();
            this.loadUser();
            // this.loadVci();
            this.loadPlan();
            this.loadMode();
             
        }
    }
</script>
