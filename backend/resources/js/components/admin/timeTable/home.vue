<template>
  <div class="p-sm-3">
    <div>
      <b-card no-body class="full-body">
        <b-tabs card class="full-body">
          <b-tab title="TimeTable" active>
            <nav class="mb-5">
              <router-link to="/admin/time-table">
                <b-button size="sm"><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon> Create TimeTable</b-button>
              </router-link>
            </nav>
            <b-table :items="table" :fields="fields" hover bordered>
              <template v-slot:cell(action)="data">
               
                 <div class="">
                  <i class="fa fa-ellipsis-v" :id="data.index+'show'" aria-hidden="true"></i>
                
                   <b-popover :target="data.index+'show'"  triggers="hover"  placement="top">
                     <ul>
                        <li class="mb-3" @click="view(data.item.id)">
                    <i class="fa fa-eye" aria-hidden="true"></i> View
                  </li>

                  <li class="mb-3" @click="edit(data.item.id)">
                    <i class="fas fa-edit"></i>Edit
                  </li>
                  <li class="mb-3" @click="drop(data.item.id)">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                  </li>
                    </ul>
                  </b-popover>
                </div>
              </template>

              <template v-slot:cell(drop)="data">
                <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
              </template>
              <template v-slot:cell(class)="data"><div class="toCaps">{{data.item.myclass}}</div></template>
            </b-table>
          </b-tab>
          <b-tab title="Live Class">
            <b-alert show dismissible>
              Create TimeTable first before creating a live class
            </b-alert>
           <b-row>
             <b-col> <b-button class="shadow-sm" @click="makeLive">Create Live Class</b-button></b-col>
             <b-col class="text-right">
               <b-form-select v-model="select" class="lev">
                 <b-form-select-option v-for="(val,id) in classes" :key="id" :value="val">
             {{val}}
                 </b-form-select-option>
               </b-form-select>
             </b-col>
           </b-row>
              <b-container fluid>
      <b-row class=" p-3">
        <b-col md="3" class="p-2" v-for="(val,id) in sorted" :key="id">
          <b-card title="Class Detail"   class="mb-2">
             <b-card-text class="toCaps">Day : {{val.day}}</b-card-text>
            <b-card-text>Subject : {{val.subject}}</b-card-text>
            <b-card-text>Tutor : {{val.tutor}}</b-card-text>
            <b-card-text>Level : {{val.level}}</b-card-text>
            <b-card-text>Start time : {{val.start_time | format }}</b-card-text>
             <b-card-text>End time :{{val.end_time | format }}</b-card-text>
            <b-card-text>
              Class Link : {{val.link}}

              <b-form-input v-model="val.link" placeholder="https://"></b-form-input>
            </b-card-text>
            <b-card-text>Password: {{val.password}}
              <b-form-input v-model="val.password" placeholder=""></b-form-input></b-card-text>

            <b-button variant="success" @click="saveLink(val)">Save Link</b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>


<script>
export default {
  props: ["admin"],
  data() {
    return {
      table: [],
      items: [],
      item: false,
      fields: ["class", "action", "drop"],
      live: [],
      classes:[],
      select:''
    };
  },
  watch: {
    item: "selectAll",
  },
  mounted() {
    this.getTable();
    this.getLive();
    this.getclasses()
  },
  computed:{
     sorted(){
      return this.live.filter(item=>item.level.toLowerCase().includes(this.select.toLowerCase().trim()))
     }
  },
  methods: {
    selectAll() {
      if (this.item) {
        this.items = [];
        this.times.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },

    getTable() {
      axios
        .get(`/api/times-table`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.table = res.data;
          }
        });
    },
 getclasses() {
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
        
             res.data.forEach((item) => {
             
              if (item.sub_class !== "") {
                item.sub_class.split(",").forEach((i) => {
                   this.classes.push(i);
                });
              }
            });
            this.select =  this.classes[0]
          }
        });
    },
    makeLive() {
      axios
        .get(`/api/make-live-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getLive()
              this.$toasted.info("Classes updated!!");
          }
        });
    },

    getLive() {
      axios
        .get(`/api/get-live-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.live = res.data;
          }
        });
    },
      saveLink(val) {
    
      axios
        .put(`/api/live-class/${val.id}`, val,{
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
          
            this.$toasted.info('Link saved')
          }
        });
    },

    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/times-table/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getTable();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      if (del) {
        axios
          .post("/api/multi-times-drop", this.items, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getTable();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
    edit(id) {
      this.$router.push(`/admin/time-table/edit/${id}`);
    },
    view(id) {
      this.$router.push(`/admin/time-table/view/${id}`);
    },
  },
};
</script>

<style scoped>
nav {
  display: grid;
 justify-content: flex-end;
}
.hiden {
  opacity: 0;
}

.body {
  padding: 20px 20px 50px;
  height: 100%;
}
.lev{
  width:150px;
  margin-left: auto;
}
</style>