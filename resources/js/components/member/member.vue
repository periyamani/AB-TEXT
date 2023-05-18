<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Members List</h3>

                <div class="card-tools">
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>S.NO</th>
                       <th>Member ID</th>
                      <th>Name</th>
                      <th>Gender</th>
                       <th>Email</th>
                        <th>Mobile</th>
                         <th>Age</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="members.data.length != 0">
                     <tr v-for="(member,index) in members.data" :key="member.id">

                      <td>{{members.from+index}}</td>
                       <td>{{member.member_id}}</td>
                      <td>{{member.name}}</td>
                       <td>{{member.gender}}</td>
                       <td>{{member.email}}</td>
                       <td>{{member.mobile}}</td>
                       <td>{{member.age}} Yrs</td>
                      <td>

                        <a href="#" @click="nextpage(member.id)">
                            <i class="fa fa-eye blue"></i>
                        </a>
                         /
                        <a href="#" @click="deleteCountry(member.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                     </tbody>
                     <tbody v-if="members.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="members" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                members : {},
               
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/members?page=' + page).then(({ data }) => {
                    
                    this.members = data.data;

                    });
                   
            },
            nextpage(id){
                window.location.href = '/member/viewprofiledata?id='+id;
            },
           
            deleteCountry(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              axios.delete('/api/membersdelete/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadCaste();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadCaste(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/members").then(({ data })  => {
                    
                    this.members = data.data;

                    });
                }
            },
        

        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadCaste();
             
        }
    }
</script>
