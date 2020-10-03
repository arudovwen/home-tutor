<template>
  <div class="p-sm-3">
    <b-modal
      id="assign-head"
      scrollable
      :title="
        JSON.parse(admin.level).includes('tertiary')
          ? 'Assign Course Advisor'
          : 'Assign Class Head'
      "
      hide-footer
    >
      <div>
        <AssignHead
          @update="updateHead"
          @toggleModal="toggleModal"
          :admin="admin"
        />
      </div>
    </b-modal>

    <b-modal id="subclass" size="lg" scrollable hide-footer>
      <b-container class=" p-3">
        <form @submit.prevent="savesubject">
          <h5 class="text-center">
            Subject
            <i class="fa fa-arrows-h mx-2" aria-hidden="true"></i> Class
          </h5>

          <b-row>
            <b-col  cols="12" sm="6">
              <label for>Class</label>
              <b-form-select v-model="choose_class">
                <b-form-select-option value>Select</b-form-select-option>
                <b-form-select-option
                  :value="myclass.id"
                  v-for="(myclass, idx) in classes"
                  :key="idx"
                  >{{ myclass.class_name }}</b-form-select-option
                >
              </b-form-select>
            </b-col>
               <b-col 
                cols="12" sm="6">
              <b-form-group label="Category">
                <b-form-select v-model="category" class="toCaps">
                  <b-form-select-option
                    :value="level"
                    v-for="(level, idx) in JSON.parse(admin.level)"
                    :key="idx"
                  >
                    <span>{{ level }}</span>
                  </b-form-select-option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
         

          <b-row>
            <b-col>
              <b-form-group label="Choose Subjects">
                <b-form-row>
                  <b-col
                    cols="4"
                    v-for="(subject, idx) in sortSubject"
                    :key="idx"
                  >
                    <b-form-checkbox
                      class="toCaps"
                      :value="subject.name.toLowerCase()"
                      v-model="selected_subjects"
                    >
                      {{ subject.name }}( <small>{{ subject.code }}</small
                      >)
                    </b-form-checkbox>
                  </b-col>
                </b-form-row>
              </b-form-group>
           </b-col>
              <b-col cols="12">
              <b-form-group class="text-center">
                <b-button type="submit">Save</b-button>
              </b-form-group>
            </b-col>
          </b-row>
        </form>
      </b-container>
    </b-modal>
    <b-modal
      id="subjects"
      scrollable
      :title="
        JSON.parse(admin.level).includes('tertiary')
          ? 'List of courses '
          : 'List of subjects'
      "
      hide-footer
    >
      <b-table-simple v-if="mysubjects">
        <b-tbody>
          <b-tr
            v-for="(item, idx) in JSON.parse(mysubjects.subjects)"
            :key="idx"
          >
            <b-td class="toCaps">{{ item }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <b-alert show v-else
        >No
        {{
          JSON.parse(admin.level).includes("tertiary")
            ? " courses "
            : "subjects"
        }}
        available</b-alert
      >
    </b-modal>

    <b-card no-body class="full-body myfac">
      <b-tabs
        :vertical="!mobile"
        justified
        :end="!mobile"
        card
        no-body
        class="full-body"
      >
        <b-tab title="Classes" active>
          <div class>
            <b-modal id="classes" scrollable hide-footer title="Add Class">
              <div class="p-2">
                <form @submit.prevent="addClass" class="mb-5">
                  <b-form-group label="Category">
                    <b-form-select v-model="data.level" class="toCaps">
                      <b-form-select-option
                        :value="level"
                        v-for="(level, idx) in JSON.parse(admin.level)"
                        :key="idx"
                      >
                        <span>{{ level }}</span>
                      </b-form-select-option>
                    </b-form-select>
                  </b-form-group>
                  <div class="form-group">
                    <label for>{{
                      JSON.parse(admin.level).includes("tertiary")
                        ? "Level"
                        : "Name"
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="data.class_name"
                      aria-describedby="helpId"
                      :placeholder="placeholder_1"
                    />
                  </div>
                  <!-- <div
                    class="form-group"
                    v-if="!JSON.parse(admin.level).includes('tertiary')"
                  >
                    <label for>Arms (Optional)</label>

                    <b-form-row>
                      <b-col
                        cols="12"
                        sm="12"
                        v-for="(val, idx) in data.arms"
                        :key="idx"
                      >
                        <b-input-group>
                          <b-form-input v-model="val.name"></b-form-input>
                          <b-input-group-append>
                            <b-button
                              variant="success-outline"
                              @click="addmore"
                            >
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <b-button
                              variant="secondary"
                              @click="removemore(idx)"
                            >
                              <b-icon icon="x"></b-icon>
                            </b-button>
                          </b-input-group-append>
                        </b-input-group>
                      </b-col>
                    </b-form-row>
                  </div> -->
                  <!-- <b-form-group
                    label="Choose Head Teacher"
                    v-if="!JSON.parse(admin.level).includes('tertiary')"
                  >
                    <b-form-select v-model="data.tutor">
                      <b-form-select-option
                        :value="tutor.id"
                        v-for="(tutor, idx) in tutors"
                        :key="idx"
                        >{{ tutor.name }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-form-group> -->
                  <b-form-group>
                    <b-form-row v-if="!update">
                      <b-col>
                        <b-button
                          type="button"
                          block
                          variant="outline-secondary"
                          @click="cancel"
                          >Cancel</b-button
                        >
                      </b-col>
                      <b-col>
                        <b-button
                          variant="success"
                          type="submit"
                          class="btn btn-primary btn-block"
                          >Save</b-button
                        >
                      </b-col>
                    </b-form-row>
                    <div v-else class="w-100">
                      <b-form-row>
                        <b-col>
                          <b-button type="button" block @click="updateN"
                            >Update</b-button
                          >
                        </b-col>
                        <b-col>
                          <b-button
                            type="button"
                            block
                            variant="outline-secondary"
                            @click="cancel"
                            >Cancel</b-button
                          >
                        </b-col>
                      </b-form-row>
                    </div>
                  </b-form-group>
                </form>
              </div>
            </b-modal>
            <div>
              <div class="left-side">
                <nav>
                  <b-button
                    class="mr-3"
                    id="addlevel"
                    size="sm"
                    v-b-modal.classes
                  >
                    <b-icon
                      icon="plus-circle-fill"
                      style="width: 14px"
                      font-scale="1"
                    ></b-icon>
                    Add Class
                  </b-button>

                  <b-button
                    class="mr-3"
                    id="addlevel"
                    size="sm"
                    v-b-modal.subclass
                  >
                    <b-icon
                      icon="plus-circle-fill"
                      style="width: 14px"
                      font-scale="1"
                    ></b-icon>
                    Assign Subjects
                  </b-button>

                  <!-- <b-button
                    size="sm"
                    @click="multiDrop"
                  >Drop {{JSON.parse(admin.level).includes('tertiary')?'Level':'Class'}}</b-button> -->
                </nav>
                <b-tooltip target="addlevel" triggers="hover"
                  >Add a new class
                </b-tooltip>
                <!-- <div v-if="JSON.parse(admin.level).includes('tertiary')">
                  <b-table
                    :items="classes"
                    :fields="fields1"
                    hover
                    bordered
                    head-variant="light"
                  >
                    <template v-slot:cell(action)="data">
                      <b-popover
                        :target="'level' + data.item.id"
                        triggers="hover"
                        placement="top"
                      >
                        <ul>
                          <li @click="choose(data.item.id)">
                            <b-icon icon="card-list"></b-icon> List
                            {{
                              JSON.parse(admin.level).includes("tertiary")
                                ? "courses"
                                : "subjects"
                            }}
                          </li>
                          <li @click="edit(data.item.id)">
                            <b-icon icon="eye"> </b-icon> Edit
                          </li>
                          <li @click="drop(data.item.id)">
                            <b-icon icon="dash-circle"></b-icon> Drop
                          </li>
                        </ul>
                      </b-popover>
                      <div class="">
                        <i
                          class="fa fa-ellipsis-v"
                          :id="'level' + data.item.id"
                          aria-hidden="true"
                        ></i>
                      </div>
                    </template>
                    <template v-slot:cell(courses)="data"
                      ><div class="toCaps" v-if="data.item.classsubject">
                        <span
                          v-for="(item, idx) in JSON.parse(
                            data.item.classsubject.subjects
                          )"
                          :key="idx"
                          >{{ item }} ,</span
                        >
                      </div>
                      <div v-else>-</div>
                    </template>
                    <template v-slot:cell(drop)="data">
                      <b-form-checkbox
                        :value="data.item.id"
                        v-model="items"
                      ></b-form-checkbox>
                    </template>
                    <template v-slot:cell(level)="data"
                      ><div class="toCaps">
                        {{ data.item.class_name }}
                      </div></template
                    >
                  </b-table>
                </div> -->

                <b-table
                  class="mytable"
                  :items="classes"
                  :fields="fields"
                  hover
                  bordered
                  head-variant="light"
                  responsive
                >
                  <template v-slot:cell(action)="data">
                    <div class="">
                      <i
                        class="fa fa-ellipsis-v"
                        :id="'advisor' + data.item.id"
                        aria-hidden="true"
                      ></i>
                      <div class="option shadow"></div>
                      <b-popover
                        :target="'advisor' + data.item.id"
                        triggers="hover"
                        placement="top"
                      >
                        <ul>
                          <li>
                            <span @click="edit(data.item.id)">
                              <i class="fas fa-edit"></i>Edit
                            </span>
                          </li>
                          <!-- <li>
                            <span class="mr-3" @click="drop(data.item.id)">
                              <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                            </span>
                          </li> -->
                        </ul>
                      </b-popover>
                    </div>
                  </template>
                  <!-- <template v-slot:cell(head_teacher)="data">
                    <div class="toCaps">
                      {{ data.item.head_teacher || "-" }}
                    </div>
                  </template> -->
                  <template v-slot:cell(drop)="data">
                    <b-form-checkbox
                      :value="data.item.id"
                      v-model="items"
                    ></b-form-checkbox>
                  </template>
                  <!-- <template v-slot:cell(arms)="data"
                    ><div class="toCaps">
                      {{ data.item.sub_class }}
                    </div></template
                  > -->
                  <template v-slot:cell(subjects)="data"
                    ><div class="toCaps" v-if="data.item.classsubject">
                      <span
                        v-for="(item, idx) in JSON.parse(
                          data.item.classsubject.subjects
                        )"
                        :key="idx"
                        >{{ item }} ,</span
                      >
                    </div></template
                  >
                </b-table>
              </div>
            </div>
          </div>
        </b-tab>
        <!-- <b-tab
          title="Course Advisors"
          v-if="JSON.parse(admin.level).includes('tertiary')"
        >
          <div class="mb-5 text-left">
            <b-button size="sm" id="assignhead" class v-b-modal.assign-head>
              <b-icon
                icon="plus-circle-fill"
                style="width: 14px"
                font-scale="1"
              ></b-icon>
              Assign
              {{
                JSON.parse(admin.level).includes("tertiary")
                  ? "Course Advisor"
                  : "Head Teacher"
              }}
            </b-button>
            <b-tooltip target="assignhead" triggers="hover"
              >Assign Course advisor to a level</b-tooltip
            >
          </div>
          <div class="mb-5">
            <div class="mx-auto">
              <b-table-simple
                class="table table-bordered table-hover"
                v-if="heads.length"
              >
                <b-thead class="thead-light">
                  <b-tr>
                    <b-th>{{
                      JSON.parse(admin.level).includes("tertiary")
                        ? "Course Advisor"
                        : "Head Teacher"
                    }}</b-th>
                    <b-th>{{
                      JSON.parse(admin.level).includes("tertiary")
                        ? "Level"
                        : "Class"
                    }}</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(item, idx) in heads" :key="idx">
                    <b-td>{{ item.tutor.name }}</b-td>
                    <b-td scope="row">{{ item.class.class_name }}</b-td>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
              <b-alert show v-else>No Class Head</b-alert>
            </div>
          </div>
        </b-tab> -->
      </b-tabs>
    </b-card>
  </div>
</template>


<script>
import AssignHead from "../tutor/assignHead";
export default {
  props: ["admin"],
  data() {
    return {
      classes: [],
      choose_class: "",
      classes: [],
      classesSubject: [],
      selected_subjects: [],
      heads: [],
      mylevel: "tertiary",
      heads: [],
      tutors: [],
      data: {
        tutor: "",
        class_name: "",
        arms: [
          {
            name: "",
          },
        ],
        level: "",
      },
      items: [],
      item: false,
      update: false,
      fields: ["class_name", "subjects", "action"],
      fields1: ["level", "courses", "action"],

      placeholder_1: "",
      placeholder_2: "",
      mobile: false,
      subjects: [],
      selected_class: null,
      category: "nursery",
    };
  },
  watch: {
    item: "selectAll",
  },
  components: {
    AssignHead,
  },
  created() {
    if (window.innerWidth < 768) {
      this.mobile = true;
    } else {
      this.mobile = false;
    }
  },
  computed: {
    sortSubject() {
      return this.subjects.filter((item) =>
        item.level.toLowerCase().includes(this.category.toLowerCase())
      );
    },
    mysubjects() {
      return this.classesSubject.find(
        (item) => item.classes_id == this.selected_class
      );
    },
  },
  mounted() {
    this.data.level = JSON.parse(this.$props.admin.level)[0];
    this.getTutors();
    this.getclasses();
    this.getclassesSubject();
    this.getSubjects();
    if (this.$props.admin.level.toLowerCase() == "secondary") {
      this.placeholder_1 = "Jss 1";
      this.placeholder_2 = "1a ,1b";
    }
    if (this.$props.admin.level.toLowerCase() == "primary") {
      this.placeholder_1 = "Primary 1";
      this.placeholder_2 = "1a ,1b";
    }
    if (this.$props.admin.level.toLowerCase() == "nursery") {
      this.placeholder_1 = "Nursery 1";
      this.placeholder_2 = "1a ,1b";
    }
    if (this.$props.admin.level.toLowerCase() == "tertiary") {
      this.placeholder_1 = "100";
      this.placeholder_2 = "-";
    }
    this.getHeads();
  },
  methods: {
    dsubjects(arr){
  return JSON.parse(arr)
    },
    choose(id) {
      this.selected_class = id;
      this.$bvModal.show("subjects");
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
    getclassesSubject() {
      axios
        .get("/api/class-subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.classesSubject = res.data;
          }
        });
    },
    savesubject() {
      let data = {
        id: this.choose_class,
        subjects: this.selected_subjects,
      };
      axios
        .post("/api/class-subject", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.success("saved");
          this.getSubjects()
            this.$bvModal.hide("subclass");
          }
        });
    },
    addmore() {
      this.data.arms.push({
        name: "",
      });
    },
    removemore(id) {
      this.data.arms.splice(id, 1);
    },
    updateHead() {
      this.getHeads();

      this.$bvModal.hide("assign-head");
    },
    cancel() {
      this.update = false;

      this.data = {
        tutor: "",
        class_name: "",
        arms: [
          {
            name: "",
          },
        ],
        level: "",
      };
      this.$bvModal.hide("classes");
    },
    toggleModal(id) {
      this.$bvModal.hide(id);
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.classes.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
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
    addClass() {
      axios
        .post("/api/classes", this.data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.data = {
              tutor: "",
              class_name: "",

              level: "",
            };
            this.getclasses();
            this.$bvModal.hide("classes");
            this.$toasted.info("Created successfully");
          }
        });
    },
    updateN() {
      axios
        .put(`/api/classes/${this.data.id}`, this.data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getclasses();

            this.update = false;
            (this.data = {
              class_name: "",

              level: "",
            }),
              this.$toasted.info("Updated successfully");
            this.$bvModal.show("classes");
          }
        });
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
            if (res.data.length) {
              this.data.tutor = res.data[0].name;
            }
          }
        });
    },
    edit(id) {
      axios
        .get(`/api/classes/${id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.data = res.data;

            this.update = true;
            this.$bvModal.show("classes");
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
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/classes/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getclasses();
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
          .post("/api/multi-classes-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getclasses();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
  },
};
</script>

<style scoped>
nav {
  display: flex;
  justify-content: flex-start;

  padding: 30px 0px;
}
.left-side {
  width: 100%;
}
.form-group {
  margin-bottom: 24px;
}
td {
  text-transform: capitalize;
}
.hiden {
  opacity: 0;
}

.add {
  background-color: #f7f8fa;
}

table {
  font-size: 14.5px;
  max-width: 900px;
  margin: 0 auto;
}
ul {
  list-style: none;
}
li {
  padding: 5px 0;
}
@media (max-width: 768px) {
  nav {
    /* flex-direction:column; */
    justify-content: space-between;
  }
  nav button {
    margin: 0 !important;
    margin-bottom: 14px !important;
  }
  .mytable table {
    font-size: 14px;
  }
  td {
    min-width: 150px;
  }
}
</style>