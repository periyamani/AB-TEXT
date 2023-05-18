<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">ECU Flash Report
<button v-if="$role.isPermission('export_ecu_flash')" type="button" class="btn btn-rounded btn-info-rgba" @click="exportCSV(livelist)" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-export"></i> Export CSV</button>
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ECU Flash Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('ecu_flash_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table" style="width:200%">
                  <thead>
                     <tr>
                                            <th>S.NO</th>
                                            <th>Dealer Email</th>
                                            <th>Model Name</th>
                                            <th>Varient Name</th>
                                            <th>VCI Serial</th>
                                            <th>VIN</th>
                                            <th>Cal ID Old</th>
                                            <th>Cal ID New</th>
                                            <th>CVN Old</th>
                                            <th>CVN New</th>
                                            <th>Odometer</th>
                                            <th>GPS</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                        </tr>
                  </thead>
                  <tbody v-if="ecuflashs.data.length != 0">


<tr v-for="(ecuflash,index) in ecuflashs.data" :key="ecuflash.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{ecuflash.dealer_email}}</td>
                                        <td >{{ecuflash.model_name}}</td>
                                        <td >{{ecuflash.varient_name}}</td>
                                        <td >{{ecuflash.vci_serial}}</td>
                                        <td >{{ecuflash.vin}}</td>
                                        <td >{{ecuflash.cal_id_old}}</td>
                                       <td >{{ecuflash.cal_id_new}}</td>
                                        <td >{{ecuflash.cvn_old}}</td>
                                        <td >{{ecuflash.cvn_new}}</td>
                                        <td >{{ecuflash.odometer}}</td>
                                        <td >{{ecuflash.gps}}</td>
                                        <td >{{ecuflash.duration}}</td>
                                        <td >{{ecuflash.status}}</td>
                                        </tr>
                     </tbody>
                     <tbody v-if="ecuflashs.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="ecuflashs" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <div v-else>
                <h6 class="text-danger"> Permission Denied </h6>
            </div>
            <!-- /.card -->
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
                ecuflashs : {},
                vehicles : [],
                 ecu : [],
                 livelist : [],
                fromData : "",
                file: '',
                form: new Form({
                    id : '',
                    name: '',
                }),
            }
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            getResults(page = 1) {

                   
                  
                  axios.get('/api/ecuflash?page=' + page).then(({ data }) => (this.ecuflashs = data.data,this.fromData = data.data.from));

                   
            },
            loadEcuFlash(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/ecuflash").then(({ data }) => (this.ecuflashs = data.data,this.fromData = data.data.from));
                }
            },
            loadexport(){
              axios.get("/api/ecuflash/export").then(({ data }) => (this.livelist = data.data));
            },
            exportCSV(arrData){
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += [
                    Object.keys(arrData[0]).join(";"),
                    ...arrData.map(item => Object.values(item).join(";"))
                ]
                    .join("\n")
                    .replace(/(^\[)|(\]$)/gm, "");

                const data = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", data);
                link.setAttribute("download", "ECU Flash Report.csv");
                link.click();
                },
            
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadEcuFlash();
            this.loadexport();
             
        }
    }
</script>
