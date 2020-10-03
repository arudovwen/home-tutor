<template>
  <!-- <div class="p-3">
    <b-modal id="student-create" scrollable hide-footer>
      <div>
        <Add @toggleModal="toggleModal" />
      </div>
    </b-modal>
    <b-modal id="student-edit" scrollable title="Update Student" size="lg" hide-footer>
      <div>
        <Edit @toggleModal="toggleModal" :id="id" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="student-assign" scrollable title="Assign Class" size="lg" hide-footer>
      <div>
        <Assign @toggleModal="toggleModal" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="assign-class" scrollable title="Assign Class " size="lg" hide-footer>
      <div>
        <AssignLevel @toggleModal="toggleModal" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="student-view" scrollable size="lg" hide-footer >
      <div>
        <ViewStudent @toggleModal="toggleModal" :admin="admin" :id="id" />
      </div>
    </b-modal>
    <div class="nav">
      <nav class="d-flex">
        <b-button size="sm" class="mr-3" v-b-modal.student-create><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon> Add Student</b-button>

        <b-button size="sm" class="mr-3" v-b-modal.student-assign v-if="!JSON.parse(admin.level).includes('tertiary')
        
        && !JSON.parse(admin.level).includes('secondary')">
          Assign
          Subjects
        </b-button>

        <b-button size="sm" id="assignlevel" class="mr-3" v-b-modal.assign-class>
         Register Students
        </b-button>
       <b-tooltip target="assignlevel"  triggers="hover">Assign student to a   {{JSON.parse(admin.level).includes('tertiary')?'Level':'class arm'}}</b-tooltip>
        <b-button size="sm" @click="multiDrop">Multi-Drop</b-button>
      </nav>
    </div>

    <div class="left-side">
      <div class="bd-table">
        <b-row>
          <b-col>
            <h4 class="toCaps mb-4">Showing : {{current== ''? 'All': current.toLowerCase()}}</h4>
          </b-col>
          <b-col class="text-right d-flex ">
            <b-form-select v-model="current" class="w-50 mr-2" size="sm">
              <b-form-select-option value disabled>Select Level</b-form-select-option>
              <b-form-select-option value>All</b-form-select-option>
              <b-form-select-option
                :value="item.class_name"
                v-for="(item,idx) in allClass"
                :key="idx"
              >{{item.class_name}}</b-form-select-option>
            </b-form-select>


            <b-form-input placeholder="Search name"  v-model="search" size="sm"></b-form-input>
          </b-col>
        </b-row>

        <b-table :items="sortedStudents" :fields="fields" hover bordered responsive>
          <template v-slot:table-busy>
            <div class="text-center my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </template>
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
           <template v-slot:cell(arm)="data">
           <span>{{data.item.sub_class}}</span>
          </template>
        </b-table>
      </div>
    </div>
  </div>-->
  <div class="main-body">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="student-table">
            <h4>Students</h4>
            <hr>
            <b-table :items="sortedStudents" :fields="fields" hover striped responsive v-if="sortedStudents.length">
              <template v-slot:table-busy>
                <div class="text-center my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  <strong>Loading...</strong>
                </div>
              </template>
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
              <template v-slot:cell(arm)="data">
                <span class="toCaps">{{data.item.sub_class}}</span>
              </template>
            </b-table>
            <b-alert v-else show>No data</b-alert>
          </div>
        </div>
      
      </div>
    </div>
  </div>
</template>


<script>
import Add from "./create";
import Edit from "./update";
import Assign from "./assign";
import AssignLevel from "./assignLevel";
import ViewStudent from "./view";
export default {
  props: ["admin"],
  data() {
    return {
      search: "",
      id: null,
      busy: true,
      students: [],
      items: [],
      item: false,
      allClass: [],
      current: "",
      fields: [
        "student_id",
        { key: "name", sortable: true },
        "email",
        { key: "gender", sortable: true },
        { key: "student_level", sortable: true },

        "action",
        "drop",
      ],
    };
  },
  components: {
    Add,
    Edit,
    Assign,
    AssignLevel,
    ViewStudent,
  },
  watch: {
    item: "selectAll",
  },
  mounted() {
    this.getStudents();
    this.getCLasses();
  },
  computed: {
    sortedStudents() {
      var search = this.students.filter((item) => {
        return item.student_level
          .toLowerCase()
          .trim()
          .includes(this.search.toLowerCase().trim());
      });
      var level = search.filter((item) => {
        return item.student_level
          .toLowerCase()
          .trim()
          .includes(this.current.toLowerCase().trim());
      });

      return level;
    },
  },
  methods: {
    toggleModal(id) {
      this.$bvModal.hide(id);
      this.getStudents();
    },
    selectClass(value) {
      this.current = value;
    },
    getCLasses() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allClass = res.data;
          }
        });
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.students.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },
    getStudents() {
      axios
        .get("/api/admin-get-students", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.students = res.data;
            this.busy = false;
          }
        });
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/student/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getStudents();
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
          .post("/api/multi-student-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getStudents();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
    edit(id) {
      this.id = id;
      this.$bvModal.show("student-edit");
    },
    view(id) {
      this.id = id;
      this.$bvModal.show("student-view");
    },
  },
};
</script>

<style scoped>
.view {
  display: flex;
}
nav {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}
.left-side {
  /* width: 80%; */
  padding: 30px 15px;
}
.hiden {
  opacity: 0;
}

.class_list {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-row-gap: 15px;
  grid-column-gap: 15px;
}
.single-class {
  padding: 15px;
  background: #f7f8fa;
  text-transform: capitalize;
  text-align: center;
}
.student-table {
  background: white;
  border-bottom: 10px;
  margin-top: 4rem;
  padding: 15px;
  border-radius: 10px;
}
.add-student {
  background: #fff;
  height: 92vh;
  margin-right: -17px;
}
.student-btn {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding-top: 15px;
}
.student-table h4 {
  padding: 10px;
}
@media(max-width:425px){
 
  .row{
    flex-direction:column-reverse
  }
  .add-student{
    height:auto;
    padding:20px 0;
      margin:0;
  }
  .student-table{
    margin-top:1rem
  }
  .mybutt{
    display: flex;
    justify-content: space-between;
    padding:10px;
  }
 
}
</style>