<template>
<div class="p-sm-3">
 <b-card no-body>
    <b-tabs card>
      <b-tab 
      title="Tutors" active>
     <div class="view">
    <b-modal id="tutor-create" scrollable  :title="JSON.parse(admin.level).includes('tertiary')?'Create Lecturer':'Create Teacher'" hide-footer>
      <div>
        <Add @update="update" @toggleModal="toggleModal" />
      </div>
    </b-modal>
    <b-modal id="tutor-edit" scrollable :title="JSON.parse(admin.level).includes('tertiary')?'Update Lecturer':'Update Teacher'" size="lg" hide-footer>
      <div>
        <Edit @update="update" @toggleModal="toggleModal" :id="id" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="tutor-assign" scrollable title="Assign Subjects" size="lg" hide-footer>
      <div>
        <Assign @update="update" @toggleModal="toggleModal" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="assign-head" scrollable title="Assign Head Teacher" size="lg" hide-footer>
      <div>
        <AssignHead @update="update" @toggleModal="toggleModal" :admin="admin" />
      </div>
    </b-modal>
    <b-modal id="tutor-view" scrollable size="lg" hide-footer>
      <div>
        <ViewTutor @update="update" @toggleModal="toggleModal" :admin="admin" :id="id" />
      </div>
    </b-modal>
      <!-- <nav class="text-right">
      <b-button  class="mr-3" size="sm" v-b-modal.tutor-create><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon> Add </b-button>

      <b-button  class=" " id="lecturecourses" size="sm" v-b-modal.tutor-assign>
        Assign
          {{JSON.parse(admin.level).includes('tertiary')?'Courses':'Subjects'}}
      </b-button>

    <b-tooltip target="lecturecourses"  triggers="hover">
      Assign courses to a {{JSON.parse(admin.level).includes('tertiary')?'lecturer':'teacher'}}
    </b-tooltip>
    </nav> -->
   
    <div class="left-side">
      <b-row>
        <b-col cols="4" class="my-3 text-left">
      <b-form-input class="" size="sm" type="text" placeholder="Search name" v-model="search"></b-form-input>
    </b-col>
      </b-row>
      <div class="tutor-table">
        <b-table bordered hover  :busy="busy" :items="sortedTutors" :fields="fields">
        <template v-slot:cell(sn)="data">{{data.index+1}}</template>
        <template v-slot:cell(drop)="data">
          <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
        </template>

        <template v-slot:cell(subject(s))="data">
         <div class="toCaps d-flex" >
           <span  v-for="(course,idx) in data.item.course" :key="idx">{{course}}, &nbsp;</span>
         </div>
        </template>

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
      </b-table>
      </div>
    </div>
  
  </div>
      </b-tab>
       <!-- <b-tab v-if="!JSON.parse(admin.level).includes('tertiary')" title="Head Teachers">
         <div class="mb-5 text-right">
             <b-button  size="sm" class="" v-b-modal.assign-head>
       Assign
      
        Head Teacher
      </b-button>
         </div>
              <div class="mb-5">
        <div class="mx-auto">
          <b-table-simple responsive class="table table-bordered table-hover" v-if="heads.length">
            <b-thead class="thead-light">
              <b-tr>
                <b-th>Head Teacher</b-th>
                <b-th>Class</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="(item,idx) in heads" :key="idx">
                <b-td>{{item.tutor.name}}</b-td>
                <b-td scope="row">{{item.class.class_name}}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <div class="form-control" v-else>No Class Head</div>
        </div>
      </div>
      </b-tab> -->
       <b-tab title="Timeline">

              <b-row class="text-left mb-3">
                  <b-col md="3">
                      <div class="text-right">
                        <b-form-select v-model="week" class="week mb-3">
                          <b-form-select-option
                            :value="num"
                            v-for="(num,id) in n"
                            :key="id"
                          >{{getWeek(new Date())==num?'Current week ':'Week' }} {{num}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
               <!-- <b-col cols="3">
                  <b-form-datepicker class="selectday" v-model="day"></b-form-datepicker>
               </b-col> -->
                 <b-col md="3">
                      <div class="text-right">
                        <b-form-select v-model="day" class="week mb-3">
                          <b-form-select-option
                            :value="num.value"
                            v-for="(num,id) in days"
                            :key="id"
                          >{{num.key}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
              </b-row>

              <div class="mytimeline">
                <div class="timeline">
                  <div
                    class="container-time"
                    v-for="(time,idx) in sortTimeline"
                    :key="idx"
                    :class="{right:idx%2,left:!idx%2}"
                  >
                    <div class="content">
                      <h5>{{time.date}}</h5>
                      <p>{{handleTimeline(time.record,time.created_at,time.tutor.name)}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </b-tab>
    </b-tabs>
  </b-card>
</div>
 
</template>


<script>
import Add from "./create";
import Edit from "./update";
import Assign from "./assign";
import AssignHead from "./assignHead";
import ViewTutor from "./view";
export default {
  props: ["admin"],
  data() {
    return {
      search:'',
      id: null,
      tutors: [],
      busy: true,
      items: [],
      item: false,
      fields: [
        "Sn",
        { key: "name", sortable: true },
        "email",
        { key: "gender", sortable: true },
        "phone",
        "subject(s)",
        "action",
       
        // "drop",
      ],
      timeline:[],
       heads:[],
      n:52,
      week:null,
      day: new Date().getDay(),
      days: [
         { key: "All", value: "" },
        { key: "Monday", value: 1 },
        { key: "Tuesday", value: 2 },
        { key: "Wednesday", value: 3 },
        { key: "Thursday", value: 4 },
        { key: "Friday", value: 5 },
        { key: "Saturday", value: 6 },
        { key: "Sunday", value: 7 },
      ],
    };
  },
  watch: {
    item: "selectAll",
  },
  components: {
    Add,
    Edit,
    Assign,
    AssignHead,
    ViewTutor,
  },
  mounted() {
    this.getAdmins();
    this.getTimeline()
    this.getHeads()
    this.week = this.getWeek(new Date());
  },
  computed:{
    sortedTutors(){
      return this.tutors.filter(item=>item.name.toLowerCase().includes(this.search.toLowerCase()))
    },
    sortTimeline() {
     var week = this.timeline.filter((item) => {
        return this.getWeek(item.date).toString().includes(this.week.toString());
      });

      return week.filter(item=> new Date(item.date).getDay() == this.day)
    }
  },
  methods: {
    update(){
  this.getAdmins(),
  this.getHeads()
  this.getTimeline()
  this.$bvModal.hide('tutor-assign')
  this.$bvModal.hide('assign-head')
    },
     getHeads() {
      axios
        .get("/api/tutor-class", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.heads = res.data.data;
          }
        });
    },
     sortTime(time) {
      // Check correct time format and split into components
      time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

      if (time.length > 1) {
        // If time format correct

        time = time.slice(1); // Remove full string match value
        time[3] = " ";
        time[5] = +time[0] < 12 ? "AM" : "PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
      }

      return time.join(""); // return adjusted time or original string
    },
      getWeek(date) {
      var date = new Date(date);
      date.setHours(0, 0, 0, 0);
      // Thursday in current week decides the year.
      date.setDate(date.getDate() + 3 - ((date.getDay() + 6) % 7));
      // January 4 is always in week 1.
      var week1 = new Date(date.getFullYear(), 0, 4);
      // Adjust to Thursday in week 1 and count number of weeks from date to week1.
      return (
        1 +
        Math.round(
          ((date.getTime() - week1.getTime()) / 86400000 -
            3 +
            ((week1.getDay() + 6) % 7)) /
            7
        )
      );
    },
     handleTimeline(type, time,name) {
      if (type == "logged") {
        return (
         name+ " logged in at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "logged out") {
        return (
            name+" logged out at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "new assessment") {
        return (
          name+  " created a new assessment at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "new resource") {
        return (
           name+ " uploaded a new resource at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "Enter class") {
        return (
           name+ " started a class at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
    },
    getTimeline() {
      axios
        .get(`/api/admin-specific-att`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.timeline = res.data;
          }
        });
    },
    toggleModal(id) {
      this.$bvModal.hide(id);
      this.getAdmins();
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.tutors.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },
    getAdmins() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
            this.busy = false;
          }
        });
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/tutor/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getAdmins();
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
          .post("/api/multi-tutor-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getAdmins();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
    edit(id) {
      this.id = id;
      this.$bvModal.show("tutor-edit");
    },
    view(id) {
      this.id = id;
      this.$bvModal.show("tutor-view");
    },
  },
};
</script>

<style scoped>

nav {
  width: 100%;
  background: white;
  padding: 30px 20px;
  display: flex;
  justify-content: flex-end;
}
.hiden {
  opacity: 0;
}
.left-side {
  width: 100%;
  padding: 30px 20px;
}
/* The actual timeline (the vertical ruler) */
.timeline {
  background-color: #474e5d;
  font-family: Helvetica, sans-serif;
  position: relative;
  max-width: 1200px;
  margin: 0 auto;

  overflow: auto;
}
.att-td {
  padding: 0;
}
/* The actual timeline (the vertical ruler) */
.mytimeline{
  max-height: 70vh;
  overflow:auto;
}
.timeline::after {
  content: "";
  position: absolute;
  width: 6px;
  background-color: white;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.container-time {
  padding: 10px 40px;
  position: relative;
  background-color: inherit;
  width: 50%;
}

/* The circles on the timeline */
.container-time::after {
  content: "";
  position: absolute;
  width: 25px;
  height: 25px;
  right: -17px;
  background-color: white;
  border: 4px solid #ff9f55;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left {
  left: 0;
}

/* Place the container to the right */
.right {
  left: 50%;
}

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -16px;
}

/* The actual content */
.content {
  padding: 20px 30px;
  background-color: white;
  position: relative;
  border-radius: 6px;
}
.content h5{
  font-size:15px;
}
.content p{
  font-size:15px;
}
.table td{
  text-transform:capitalize;
}
/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
  /* Place the timelime to the left */
  .timeline::after {
    left: 31px;
  }
.content{
  padding:20px 15px;
}
  /* Full-width containers */
  .container-time {
    width: 100%;
    padding-left: 70px;
    padding-right: 25px;
  }

  /* Make sure that all arrows are pointing leftwards */
  .container-time::before {
    left: 60px;
    border: medium solid white;
    border-width: 10px 10px 10px 0;
    border-color: transparent white transparent transparent;
  }

  /* Make sure all circles are at the same spot */
  .left::after,
  .right::after {
    left: 15px;
  }

  /* Make all right containers behave like the left ones */
  .right {
    left: 0%;
  }
  .schedule-inner{
    padding:0
  }
  td{
    min-width:150px;
  }
  .tutor-table{
    overflow: scroll;
  }
}
</style>