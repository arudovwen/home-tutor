<template>
  <!-- <div class="body">
     <h4 class="mb-4">All Syllabus</h4>
    <nav class="mb-3">
      <router-link to="/admin/syllabus">
        <b-button size="sm" class="mr-3">
        <b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon> Create Syllabus     
        </b-button>
      </router-link>
      <b-button size="sm"  @click="multiDrop">
       Multi-Drop
      
      </b-button>
     
     
     
    </nav>

  

  
      <b-table :items="syllabus" :fields="fields" hover bordered>
            <template v-slot:cell(action)="data">
          <div class="options">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            <div class="option shadow">
              <ul>
                <li>
                  <span @click="view(data.item.id)" class="mr-3">
                    <i class="fas fa-eye"></i>View
                  </span>
                </li>
                <li>
                  <span @click="edit(data.item.id)">
                    <i class="fas fa-edit"></i>Edit
                  </span>
                </li>
                <li>
                  <span class="mr-3" @click="drop(data.item.id)">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </template>

        <template v-slot:cell(drop)="data">
          <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
        </template>
          <template v-slot:cell(class)="data">
            <div  class="toCaps">   {{data.item.myclass}}</div>
      
        </template>

        <template v-slot:cell(subject)="data">
            <div  class="toCaps">   {{data.item.subject}}</div>
      
        </template>
          <template v-slot:cell(category)="data">
       <div class="toCaps">  {{JSON.parse(data.item.syllabus).level}}</div>
        </template>
      </b-table>
  
  </div>-->
  <div class="main-body">
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <div class="syllabus-table">
            <h5>Syllabus</h5>
            <hr />
            <b-table :items="syllabus" :fields="fields" hover striped responsive>
              <template v-slot:cell(action)="data">
                <div class="">
                  <i class="fa fa-ellipsis-v" :id="data.index+'show'" aria-hidden="true"></i>
                
                   <b-popover :target="data.index+'show'"  triggers="hover"  placement="top">
                     <ul>
                      <li>
                        <span @click="view(data.item.id)" class="mr-3">
                          <i class="fas fa-eye"></i>View
                        </span>
                      </li>
                      <li>
                        <span @click="edit(data.item.id)">
                          <i class="fas fa-edit"></i>Edit
                        </span>
                      </li>
                      <li>
                        <span class="mr-3" @click="drop(data.item.id)">
                          <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                        </span>
                      </li>
                    </ul>
                  </b-popover>
                </div>
              </template>

              <template v-slot:cell(drop)="data">
                <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
              </template>
              <template v-slot:cell(class)="data">
                <div class="toCaps">{{data.item.myclass}}</div>
              </template>

              <template v-slot:cell(subject)="data">
                <div class="toCaps">{{data.item.subject}}</div>
              </template>
              <template v-slot:cell(category)="data">
                <div class="toCaps">{{JSON.parse(data.item.syllabus).level}}</div>
              </template>
            </b-table>
          </div>
        </div>
        <div class="col-md-3">
          <div class="add-syllabus">
            <router-link to="/admin/syllabus">
              <div class="syllabus-btn">
                <b-button size="sm">Create Syllabus</b-button>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["admin"],
  data() {
    return {
      syllabus: [],
      items: [],
      item: false,
      fields: ["class", "subject", "action", "drop"],
    };
  },
  watch: {
    item: "selectAll",
  },
  mounted() {
    this.getSyllabus();
  },
  methods: {
    selectAll() {
      if (this.item) {
        this.items = [];
        this.syllabus.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },
    getSyllabus() {
      axios
        .get("/api/syllabus", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.syllabus = res.data;
          }
        });
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/syllabus/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getSyllabus();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      let data = {
        data: this.items,
      };
      if (del) {
        axios
          .post("/api/multi-syllabus-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getSyllabus();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
    edit(id) {
      this.$router.push(`/admin/syllabus/edit/${id}`);
    },
    view(id) {
      this.$router.push(`/admin/syllabus/view/${id}`);
    },
  },
};
</script>

<style scoped>
nav {
  display: flex;

  justify-content: flex-end;
}
.hiden {
  opacity: 0;
}

.body {
  padding: 20px 20px 50px;
  height: 100%;
}
table {
  margin: 0 auto;
  max-width: 900px;
}
.table td {
  text-transform: capitalize;
}
.syllabus-table {
  background: white;
  border-bottom: 10px;
  margin-top: 4rem;
  padding: 15px;
    border-radius: 10px;
}
.syllabus-table h4 {
  padding: 10px;
}
.add-syllabus {
  background: #fff;
  height: 92vh;
  margin-right: -17px;
}
.syllabus-btn {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding-top: 15px;
}
@media(max-width:425px){
 
  .row{
    flex-direction:column-reverse
  }
  .add-syllabus{
    height:auto;

    margin:0;
  
  }
  .syllabus-btn{
    padding:20px 0;
  }
  .syllabus-table{
    margin-top:1rem;
  }
}
</style>