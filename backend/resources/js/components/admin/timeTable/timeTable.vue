<template>
  <div class="body">
    <h4 class="mb-4">Time Table</h4>

    <b-row>
      <b-col md="3">
        <b-form-group label="Category">
          <b-form-select size="sm" v-model="level" class="toCaps">
            <b-form-select-option value="selected">Select</b-form-select-option>
            <b-form-select-option
              class="toCaps"
              :value="level"
              v-for="(level,idx) in JSON.parse(admin.level)"
              :key="idx"
            >{{level}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>

      <b-col md="3" v-if="level=='tertiary'">
        <b-form-group label=" Level">
          <b-form-select size="sm" required class="custom-select toCaps " v-model="my_class">
            <option selected disabled value>Select one</option>
            <b-form-select-option
              :value="item.class_name.toLowerCase().trim()"
              v-for="(item,idx) in sortedClass"
              class="toCaps"
              :key="idx"
            >{{item.class_name.toLowerCase().trim()}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>

      <b-col md="3" v-if="level!=='tertiary'">
        <b-form-group label="Grade Level">
          <b-form-select size="sm" required class="custom-select toCaps" v-model="my_class">
            <option selected disabled value>Select one</option>
            <b-form-select-option
              :value="item.class_name.toLowerCase().trim()"
              v-for="(item,idx) in sortedClass"
              class="toCaps"
              :key="idx"
            >{{item.class_name.toLowerCase().trim()}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col md="3" v-if="level!=='tertiary'">
        <b-form-group label="Arm">
          <b-form-select size="sm" required class="custom-select toCaps" v-model="myclass">
            <option selected disabled value>Select one</option>
            <b-form-select-option
              :value="item.toLowerCase().trim()"
              v-for="(item,idx) in sortedArms"
              class="toCaps"
              :key="idx"
            >{{item.toLowerCase().trim()}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>
    <div class="table-responsive">
      <b-table-simple class="table table-bordered">
        <b-thead class="thead-light">
          <b-tr>
            <b-th>Day</b-th>
            <b-th>Time</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(tab,index) in table" :key="index">
            <b-td class="toCaps day">
              <b-form-select size="sm" class="form-control toCaps" v-model="tab.day">
                <option v-for="(item,idx) in days" :key="idx" :value="item" class="toCaps">{{item}}</option>
              </b-form-select>
              <div class="d-flex mt-3" v-if="table.length-1  == index">
                <b-button size="sm" @click="addDay" class="mr-3">
                  <i class="fa fa-plus-circle" aria-hidden="b-true"></i>
                </b-button>
                <b-button size="sm" @click="removeDay">
                  <i class="fa fa-times-circle" aria-hidden="b-true"></i>
                </b-button>
              </div>
            </b-td>
            <b-td class="d-flex">
              <div
                class="form-group mr-3"
                style="width:350px"
                v-for="(item,idx) in tab.courses"
                :key="idx"
              >
                <span class="d-flex">
                  <div class="w-50">
                    <label for>Start</label>

                    <b-form-timepicker size="sm" :hour12="true" v-model="item.start" locale="en"></b-form-timepicker>
                  </div>
                  <div class="w-50">
                    <label for>End</label>

                    <b-form-timepicker size="sm" :hour12="true" v-model="item.end" locale="en"></b-form-timepicker>
                  </div>
                </span>

                <b-form-select
                  size="sm"
                  class="form-control toCaps"
                  v-model="item.subject"
                  v-if="sortedSubject.length"
                >
                  <b-form-select-option value disabled>Select Subject</b-form-select-option>
                  <b-form-select-option
                    v-for="(item,idx) in JSON.parse(sortedSubject[0].classsubject.subjects)"
                    :key="idx"
                    :value="item"
                    class="toCaps"
                  >{{item}}</b-form-select-option>
                </b-form-select>

                <b-form-select size="sm" class="form-control toCaps" v-model="item.tutor">
                  <b-form-select-option value disabled>Select Tutor</b-form-select-option>
                  <b-form-select-option
                    v-for="(item,idx) in sortTutors(item.subject)"
                    :key="idx"
                    :value="item.name"
                    class="toCaps"
                  >{{item.name}}</b-form-select-option>
                </b-form-select>
              </div>
              <div class style="width:160px">
                <b-button size="sm" class="mr-2" @click="addCourse(index)">
                  <i class="fa fa-plus-circle" aria-hidden="b-true"></i>
                </b-button>
                <b-button size="sm" @click="removeCourse(index)">
                  <i class="fa fa-times-circle" aria-hidden="b-true"></i>
                </b-button>
              </div>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>

      <b-button class="my-4" @click="createTimeTable">Create</b-button>
    </div>
  </div>
</template>


<script>
export default {
  props: ["admin"],
  data() {
    return {
      tutors: [],
      level: "selected",
      my_class: "",
      days: [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
      ],
      subjects: [],
      classes: [],
      myclass: "",
      table: [
        {
          day: "Monday",
          courses: [
            {
              start: "",
              end: "",
              subject: "",
              tutor: "",
            },
          ],
        },
      ],

      items: [],
      item: false,
    };
  },
  watch: {
    item: "selectAll",
  },
  mounted() {
    this.getSubjects();
    this.getclasses();
    this.getAdmins();
    this.level = JSON.parse(this.$props.admin.level)[0];
  },
  computed: {
    sortedSubject() {
      if (this.level !== "tertiary") {
        var subjects = this.sortedClass.filter((item) =>
          item.class_name.toLowerCase().includes(this.my_class.toLowerCase())
        );
      } else {
        var subjects = this.sortedClass.filter((item) =>
          item.class_name.toLowerCase().includes(this.myclass.toLowerCase())
        );
      }

      return subjects;
    },
    sortedClass() {
      var subjects = this.classes.filter((item) =>
        item.level.toLowerCase().includes(this.level.toLowerCase())
      );

      return subjects;
    },
    sortedArms() {
      if (this.sortedClass.length) {
        return this.sortedClass[0].sub_class.split(",");
      }
    },
  },
  methods: {
    sortTutors(subject) {
      var tutors = [];
      this.tutors.forEach((item) => {
        if (item.course.includes(subject.toLowerCase())) {
          tutors.push(item);
        }
      });

      return tutors;
    },
    createTimeTable() {
      var data = {}
      if (this.level == 'tertiary') {
         data = {
        myclass: this.my_class,
        table: this.table,
        category:this.level,
         my_class:this.my_class
      };
      }else{
         data = {
        myclass: this.myclass,
        table: this.table,
        category:this.level,
         my_class:this.my_class
      };
      }
      
      axios
        .post("/api/times-table", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.info(" Created");
            this.$router.push("/admin/time-table/home");
          } else {
            this.$toasted.info("Already created");
          }
        });
    },
    getSubjects() {
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
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
            this.classes = res.data;
          }
        });
    },
    addDay() {
      this.table.push({
        day: "",
        courses: [
          {
            start: "",
            end: "",
            subject: "",
          },
        ],
      });
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
          }
        });
    },
    removeDay() {
      this.table.pop();
    },
    addCourse(id) {
      this.table[id].courses.push({
        start: "",
        end: "",
        subject: "",
      });
    },
    removeCourse(id) {
      this.table[id].courses.pop();
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.subjects.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
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
              this.getTutors();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      if (del) {
        axios
          .post("/api/multi-subjects-drop", this.items, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getsubjects();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
    edit(id) {
      this.$router.push(`/admin/subject/edit/${id}`);
    },
  },
};
</script>

<style scoped>
nav {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-column-gap: 30px;
}
.hiden {
  opacity: 0;
}
.nav_box {
  background-color: #f7f8fa;
  display: flex;
  text-align: center;
  padding: 10px 15px;
}
.add {
  background-color: #f7f8fa;
}

table {
  font-size: 14px;
}
.day .form-conb-trol {
  width: 150px;
}
</style>