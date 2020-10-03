<template>
  <b-container fluid>
    <div class="main">
      <div class="top-box">
        <div class="box">
          <b-iconstack font-scale="4" class="mr-4">
            <b-icon stacked icon="circle-fill" variant="info"></b-icon>
            <b-icon stacked icon="globe" scale="0.5" variant="white"></b-icon>
          </b-iconstack>
          <div>
            <h5>My Classes</h5>
           {{JSON.parse(tutor.levels).length}}
          </div>
        </div>
        <div class="box">
          <b-iconstack font-scale="4" class="mr-4">
            <b-icon stacked icon="circle-fill" variant="info"></b-icon>
            <b-icon
              stacked
              icon="people-fill"
              scale="0.5"
              variant="white"
            ></b-icon>
          </b-iconstack>
          <div>
            <h5>My Students</h5>
            20 +
          </div>
        </div>
        <div class="box">
          <b-iconstack font-scale="4" class="mr-4">
            <b-icon stacked icon="circle-fill" variant="info"></b-icon>
            <b-icon
              stacked
              icon="bag-check-fill"
              scale="0.5"
              variant="white"
            ></b-icon>
          </b-iconstack>
          <div>
            <h5>My Resources</h5>
            20 +
          </div>
        </div>
      </div>

      <div class="middle-box">
        <div class="box">
         <div class="sub-box" @click="gotoHere('resources')">
            <div class="mb-2 text-center">
              <b-iconstack font-scale="3">
                <b-icon stacked icon="circle-fill" variant="info"></b-icon>
                <b-icon
                  stacked
                  icon="bag-check-fill"
                  scale="0.5"
                  variant="white"
                ></b-icon>
              </b-iconstack>
            </div>
            <div>
              <h6>View Resources</h6>
            </div>
          </div>
          <div class="sub-box" @click="gotoHere('create')">
            <div class="mb-2 text-center">
              <b-iconstack font-scale="3">
                <b-icon stacked icon="circle-fill" variant="info"></b-icon>
                <b-icon
                  stacked
                  icon="bag-check-fill"
                  scale="0.5"
                  variant="white"
                ></b-icon>
              </b-iconstack>
            </div>
            <div>
              <h6>Add Resource</h6>
            </div>
          </div>
          <div class="sub-box">
            <div class="mb-2 text-center">
              <b-iconstack font-scale="3" class="mb-2">
                <b-icon stacked icon="circle-fill" variant="info"></b-icon>
                <b-icon
                  stacked
                  icon="bag-check-fill"
                  scale="0.5"
                  variant="white"
                ></b-icon>
              </b-iconstack>
            </div>

            <div>
              <h6>Draft Resources</h6>
            </div>
          </div>
        </div>
        <div class="box">
           <div class="chart">
            <column-chart :messages="{empty: 'No data'}" xtitle="Classes" ytitle="Students"  :colors="colors" id="users-chart"></column-chart>
           
          </div>
        </div>
        <div class="box">
         <div class="sub-box">
            <div >
              <h5>21</h5>
            <h6>Followers</h6>
            </div>
              <div >
              <h5>22</h5>
            <h6>Likes</h6>
            </div>
          </div>
        <div class="sub-box">
             <div class="mb-2 text-center">
              <b-iconstack font-scale="3">
                <b-icon stacked icon="circle-fill" variant="info"></b-icon>
                <b-icon
                  stacked
                  icon="inbox-fill"
                  scale="0.5"
                  variant="white"
                ></b-icon>
              </b-iconstack>
            </div>
            <div>
              <h6>Messages</h6>
            </div>
          </div>
         <div class="sub-box">
             <div class="mb-2 text-center" @click="gotoHere('assess')">
              <b-iconstack font-scale="3">
                <b-icon stacked icon="circle-fill" variant="info"></b-icon>
                <b-icon
                  stacked
                  icon="book-fill"
                  scale="0.5"
                  variant="white"
                ></b-icon>
              </b-iconstack>
            </div>
            <div>
              <h6>Assessments</h6>
            </div>
          </div>
        </div>
      </div>
      <div class="bottom-box">
        <div class="box">
          <div class="activity-cards">
            <div class="cards">
              <h5>Today's Schedule</h5>
              <div class="skelenton" v-if="loader_3">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
              <div v-else>
                <div class="class_section" v-if="todaysClass.length">
                  <div
                    class="class-content border-bottom p-2"
                    v-for="(item, idx) in todaysClass"
                    :key="idx"
                  >
                    <div class="class-content-top">
                      <h6 class="toCaps">{{ item.subject }}</h6>
                      <p>{{ item.level }}</p>
                      <i
                        class="fa fa-play-circle-o green"
                        v-if="
                          getSecond(today) > getSecond(item.start) &&
                          getSecond(today) < getSecond(item.end)
                        "
                        aria-hidden="true"
                      ></i>

                      <i
                        class="fa fa-dot-circle-o amber"
                        v-else-if="
                          getSecond(today) < getSecond(item.start) &&
                          getSecond(today) < getSecond(item.end)
                        "
                        aria-hidden="true"
                      ></i>

                      <i
                        class="fa fa-stop-circle-o red"
                        :id="idx.toString()"
                        v-else-if="
                          getSecond(today) > getSecond(item.start) &&
                          getSecond(today) > getSecond(item.end)
                        "
                        aria-hidden="true"
                      ></i>
                    </div>
                    <small
                      >{{ item.start | format }} to
                      {{ item.end | format }}</small
                    >
                    <div class="class-content-main">
                      <!-- <p>
                      <span>
                        by:
                        <strong class="toCaps">{{item.tutor}}</strong>
                      </span>
                    </p> -->

                      <p
                        v-if="
                          getSecond(today) > getSecond(item.start) &&
                          getSecond(today) < getSecond(item.end)
                        "
                      >
                        Ongoing
                      </p>
                      <p
                        v-else-if="
                          getSecond(today) < getSecond(item.start) &&
                          getSecond(today) < getSecond(item.end)
                        "
                      >
                        Upcoming
                      </p>

                      <p
                        v-else-if="
                          getSecond(today) > getSecond(item.start) &&
                          getSecond(today) > getSecond(item.end)
                        "
                      >
                        Finished
                      </p>
                    </div>
                  </div>
                </div>
                <b-alert v-else show class="text-center mt-5"
                  >No class scheduled</b-alert
                >
              </div>
              <div class="log-link" v-if="todaysClass.length">
                <router-link to="/tutor/activities">View all</router-link>
              </div>
            </div>
          </div>
        </div>
        <div class="box">
          <div class="activity-cards">
            <div class="discussion cards">
              <h5>Discussion</h5>
              <div class="skelenton" v-if="loader_4">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
              <div v-else>
                <div class="main-note" v-if="groups.length">
                  <div
                    class="discussion-content"
                    v-for="(group, idx) in groups"
                    :key="idx"
                  >
                    <div class="discussion-content-top">
                      <router-link to="/tutor/groups">
                        <p>
                          <strong class="toCaps check">{{ group.name }}</strong>
                        </p>
                      </router-link>

                      <b-avatar-group size="2.5rem">
                        <b-avatar class="profile-img"></b-avatar>

                        <b-avatar text="BV" variant="primary"></b-avatar>
                        <b-avatar
                          src="https://placekitten.com/300/300"
                          variant="info"
                        ></b-avatar>
                      </b-avatar-group>
                    </div>
                    <p v-if="group.messages.length">
                      {{ group.messages[group.messages.length - 1].message }}
                    </p>
                    <div class="discussion-content-bottom">
                      <p v-if="group.messages.length">
                        Updated
                        {{
                          group.messages[group.messages.length - 1].created_at
                            | moment("from", "now")
                        }}
                      </p>
                      <div class="notify-discussion">
                        <p>3 New Messages</p>
                      </div>
                    </div>
                  </div>
                </div>
                <b-alert show v-else class="text-center mt-5"
                  >No discussion available</b-alert
                >
              </div>
            </div>
          </div>
        </div>
        <div class="box">
          <div class="activity-cards">
            <div class="notes cards">
              <div class="notes-top">
                <h5>My Notes</h5>
                <div class="btn btn-sm" v-b-modal.note>
                  <b-icon
                    icon="plus-circle"
                    style="width: 14px"
                    font-scale="1"
                  ></b-icon>
                  <span>ADD</span>
                </div>
              </div>
              <div class="skelenton" v-if="loader_5">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
              <div v-else>
                <div class="main-note" v-if="notes.length">
                  <div
                    class="note-body"
                    v-for="(note, idx) in notes"
                    :key="idx"
                  >
                    <div class="mr-2">
                      <b-icon icon="stickies-fill" class="my-icon"></b-icon>
                    </div>
                    <div class="notes-content w-100">
                      <div class="notes-content-top">
                        <h6 class="title mr-3 mb-2">{{ note.title }}</h6>
                      </div>
                      <div class="notes-content-main">
                        <p class>{{ note.note }}</p>
                        <div
                          class="notes-date d-flex align-items-center justify-content-between w-100"
                        >
                          <p>
                            Posted:
                            <strong>{{
                              note.created_at | moment("DD/MM/YYYY")
                            }}</strong>
                          </p>

                          <b-icon
                            icon="trash"
                            class="my-icon cpointer"
                            @click="remove(note.id)"
                          ></b-icon>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <b-alert show class="text-center mt-5" v-else
                  >No note yet</b-alert
                >
              </div>
              <div class="log-link" v-if="notes.length">
                <div v-b-modal.all-note class="check_it">View All</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="note" title="New note" hide-footer>
      <b-form @submit.prevent="addNote">
        <b-form-group class="mb-3">
          <label for>Title</label>
          <b-form-input
            required
            v-model="note.title"
            placeholder="Enter title"
          ></b-form-input>
        </b-form-group>
        <b-form-group class="mb-3">
          <label for>Note</label>
          <b-form-textarea
            maxlength="200"
            required
            v-model="note.description"
            rows="3"
            placeholder="Write note here"
          ></b-form-textarea>
        </b-form-group>
        <b-form-group class="mb-3">
          <b-button variant="darkgreen" size="sm" type="submit"
            ><b-icon
              icon="plus-circle-fill"
              style="width: 14px"
              font-scale="1"
            ></b-icon>
            Add note</b-button
          >
        </b-form-group>
      </b-form>
    </b-modal>
    <b-modal id="all-note" title="My notes" hide-footer>
      <div class>
        <div class="note-body" v-for="(note, idx) in notes" :key="idx">
          <div class="mr-2">
            <b-icon icon="stickies-fill" class="my-icon"></b-icon>
          </div>
          <div class="notes-content w-100">
            <div class="notes-content-top">
              <h6 class="title mr-3 mb-2">{{ note.title }}</h6>
            </div>
            <div class="notes-content-main">
              <p class>{{ note.note }}</p>
              <div
                class="notes-date d-flex align-items-center justify-content-between w-100"
              >
                <p>
                  Posted:
                  <strong>{{ note.created_at | moment("DD/MM/YYYY") }}</strong>
                </p>
                <b-icon
                  icon="trash"
                  class="my-icon cpointer"
                  @click="remove(note.id)"
                ></b-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </b-container>
</template>


<script>
import Announcement from "../announcement";
export default {
  props: ["tutor", "groups"],
  data() {
    return {
      colors:['#13a699'],
      tutors: [],
      draftResult: [],
      allStudents: [],
      students: [],
      classes: [],
      syllabus: [],
      groupMessage: [],
      curriculum: [],
      fields: ["class", "subject"],
      field: ["class"],
      note: {
        title: "",
        description: "",
      },
      notes: [],
      todaysClass: [],
      modules: [],
      resource: [],
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
      resources: [],

      assess: [],
      allClass: [],
      swiperOptions: {
        autoplay: {
          delay: 5000,
        },
        loop: true,
        pagination: {
          el: ".swiper-pagination",
          dynamicBullets: true,
          clickable: true,
        },
        // Some Swiper option/callback...
      },
      loader_1: true,
      loader_2: true,
      loader_3: true,
      loader_4: true,
      loader_5: true,
    };
  },
  components: {
    Announcement,
  },
  mounted() {
    this.getNotes();
    this.getResources();
    this.getTodayClass();
    this.getData();
    this.getStudent();
    this.getDraftResult();
    this.getallmessages();
    this.getClasses();
    this.getUp();
    if (this.$route.query.redirect_from == "auth") {
      this.recordTimeline("logged");
    }
  },
  computed: {
    pendingResource() {
      return this.resources.filter((item) => item.status == "pending");
    },
    approvedResource() {
      return this.resources.filter((item) => item.status == "approved");
    },
  },
  methods: {
    gotoHere(val){
       if(val=='resources'){
          this.$router.push('/tutor/resources')
       }
       if(val=='create'){
          this.$router.push('/tutor/resource/create')
       }
       if(val=='assess'){
          this.$router.push('/tutor/assessment')
       }
    },
    pendingModules(arr1, arr2) {
      var arr = [];
      var ans = [];
      arr2.forEach((item) => {
        arr.push(item.module.toLowerCase());
      });

      arr1.forEach((item) => {
        if (!arr.includes(item.toLowerCase())) {
          ans.push(item);
        }
      });
      return ans;
    },
    handleAssess(level) {
      return this.draftResult.filter(
        (item) => item.level.toLowerCase() == level.toLowerCase()
      );
    },
    handleStudent(level) {
      return this.allStudents.filter(
        (item) => item.student_level.toLowerCase() == level.toLowerCase()
      );
    },
    getClasses() {
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allClass = res.data;
            this.loader_1 = false;
          }
        });
    },
    getallmessages() {
      axios
        .get(`/api/tutormessages`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          this.groupMessage = res.data;
          this.loader_4 = false;
        });
    },
    getStudent() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/all-students`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allStudents = res.data;
          }
        });
    },
    getDraftResult() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let data = {
        title: "",
      };
      axios
        .post(`/api/draft-result`, data, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.draftResult = res.data;
          }
        });
    },
    getData() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));

      axios
        .get(`/api/assessment`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.assess = res.data;
          }
        })
        .catch();
    },
    getResources() {
      axios
        .get("/api/resource", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.resources = res.data;
            this.loader_2 = false;
          }
        });
    },
    getSecond(hms) {
      var a = hms.split(":"); // split it at the colons

      // minutes are worth 60 seconds. Hours are worth 60 minutes.
      var seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
      return seconds;
    },
    getTodayClass() {
      axios
        .get(`/api/current-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.todaysClass = res.data;
            this.loader_3 = false;
          }
        });
    },
    getUp() {
      axios
        .get(`/api/tutor-upload`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.modules = res.data.modules;
            this.resource = res.data.resources;
          }
        });
    },
    remove(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/delete-note/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.$toasted.info("Note removed");
              this.getNotes();
            }
          });
      }
    },
    gotoHer(id) {
      this.$router.push(`/student/resource/view/${id}`);
    },
    addNote() {
      let data = {
        title: this.note.title,
        description: this.note.description,
      };
      axios
        .post("/api/tutor-note", data, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.note.description = "";
            this.note.title = "";
            this.getNotes();
            this.$toasted.success("Note created");
            this.$bvModal.hide("note");
          }
        })
        .catch();
    },
    getNotes() {
      axios
        .get(`/api/tutor-note`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.notes = res.data;
            this.loader_5 = false;
          }
        });
    },

    getCurriculum() {
      axios
        .get("/api/curriculum", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.curriculum = res.data;
          }
        });
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },

    getSyllabus() {
      axios
        .get("/api/syllabus", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.syllabus = res.data;
          }
        });
    },
  },
};
</script>

<style scoped>
.overview-board {
  padding-top: 20px;
}
.main {
  display: grid;
  grid-template-rows: 15% 60% 35%;
  grid-row-gap: 20px;
  height: 95vh;
  background: transparent;
  padding: 20px 0;
}

.top-box,
.bottom-box {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 20px;
}
.top-box .box {
  border-radius: 5px;
  background: white;
  box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.25);
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 20px;
}
.middle-box {
  display: grid;
  grid-template-columns: 1fr 3fr 1fr;
  grid-column-gap: 20px;
}

.middle-box .box:nth-child(1) {
  display: grid;
  grid-template-rows: 1fr 1fr 1fr;
  grid-row-gap: 20px;
}
.middle-box .box:nth-child(1) .sub-box {
  background: white;
  box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.25);
  border-radius: 5px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 15px;
    cursor: pointer;
}
.middle-box .box:nth-child(2) {
  border-radius: 5px;
  box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.25);
  background: white;
}

.middle-box .box:nth-child(3) {
  display: grid;
  grid-template-rows: 1fr 1fr 1fr;
  grid-row-gap: 20px;
}
.middle-box .box:nth-child(3) .sub-box {
  background: white;
  box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.25);
  border-radius: 5px;
   display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 15px;
  cursor: pointer;
}
.middle-box .box:nth-child(3) .sub-box:nth-child(2){
   display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 15px;

}
.middle-box .box:nth-child(3) .sub-box:nth-child(3){
   display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 15px;

}
.middle-box .box:nth-child(3) .sub-box div{
  text-align: center;
    cursor: pointer;
}

.middle-box .box:nth-child(3) .sub-box:nth-child(2){
  text-align: center;
}
.bottom-box .box {
  background: white;
  border-radius: 5px;
  padding: 15px;
  box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.25);
}
.box {
  min-height: 100%;
  max-height: 100%;
}
.note-body {
  display: flex;
  border-bottom: 1px solid #ccc;
  margin-bottom: 14px;
}
.m-res {
  max-height: 100%;
  overflow: auto;
}
.main-note {
  max-height: 100%;
  overflow: auto;
}
.skelenton {
  display: grid;
  height: 100%;
}
.assignment-overview-board {
  background: #fff;
  font-family: "Montserrat";
  padding: 15px;
  border-radius: 10px;
  height: 100%;
}
.assignment-overview-board p {
  font-weight: 600;
  color: #808080;
}
.text-center p {
  font-weight: 500;
  margin-bottom: 10px;
}
.text-center h6 {
  font-weight: 700;
}
.overview-log-1 {
  border-right: 1px solid #c4c4c4;
  margin-bottom: 10px;
}
.overview-log-4 {
  border-right: 1px solid #c4c4c4;
  margin-bottom: 10px;
}

.overview-log-1 i {
  color: #22cade;
  background: rgba(34, 202, 222, 0.25);
  border-radius: 50%;
  padding: 10px;
}
.overview-log-2 i {
  color: #dfd291;
  background: rgba(223, 210, 145, 0.25);
  border-radius: 50%;
  padding: 10px;
}
.overview-log-3 i {
  color: #ff0000;
  background: rgba(255, 0, 0, 0.25);
  border-radius: 50%;
  padding: 10px;
}
.overview-log-4 i {
  color: #008e3a;
  background: rgba(0, 142, 58, 0.25);
  border-radius: 50%;
  padding: 10px;
}
.log-link {
  display: flex;
  justify-content: flex-end;
  padding-top: 1rem;
}
.log-link a {
  color: #22cade;
}
.content-cards {
  padding-top: 2rem;
}

.class-content {
  padding-bottom: 10px;
}
.class_section {
  height: 340px;
  overflow: auto;
}
.class-content-top {
  display: flex;
  justify-content: space-between;
}

.class-content-main {
  display: flex;
  justify-content: space-between;
}
.class-content-main p {
  font-size: 12px;
}
.notes-content {
  border-bottom: 1px solid #e4e4e4;
}
.notes-content:last-child {
  border-bottom: none;
}
.check_it {
  color: #22cade;
  font-family: "Montserrat";
}
.red {
  color: #ff0000;
}
.green {
  color: green;
}
.notes-top {
  display: flex;
  justify-content: space-between;
  /* padding-bottom: 1rem; */
}
.notes-content-top {
  display: flex;
}
.notes-content-top h6 {
  margin-bottom: 0;
}
.notes-content {
}
.notes-content-main {
  padding-bottom: 5px;
}
.notes-content-main p {
  font-size: 12px;
  margin-bottom: 0;
}
.notes-date {
  color: #808080;
  padding-top: 5px;
}
.fa-file-o {
  color: #22cade;
  background: rgba(34, 202, 222, 0.25);
  border-radius: 50%;
  padding: 9px;
}
.btn {
  border: 1px solid #0a4065;
  color: #0a4065;
  font-size: 15px;
}
.discussion-content {
  border-bottom: 1px solid #e4e4e4;
  padding-bottom: 10px;
  padding-top: 5px;
}
.discussion-content:last-child {
  border-bottom: none;
}
.discussion-content p {
  font-size: 14px;
}
.discussion-content-top {
  display: flex;
  justify-content: space-between;
}
.discussion-content-top p {
  font-size: 14px;
}
.discussion-content-bottom {
  display: flex;
  justify-content: space-between;
}
.discussion-content-bottom p {
  font-size: 12px;
  margin-bottom: 0;
}
.notify-discussion p {
  background: #e4e4e4;
  color: #808080;
  border-radius: 5px;
  font-weight: 500;
  padding: 5px 10px;
}
.my-icon {
  color: #22cade;
}
@media screen and (max-width: 900px) {
  .assignment {
    padding-bottom: 10px;
  }
  .overview-log-2 {
    padding: 15px 0;
  }
  .overview-log-3 {
    padding: 15px 0;
  }
  .overview-log-1 {
    border-bottom: 1px solid #c4c4c4;
    border-right: none;
    padding: 15px 0;
  }
  .overview-log-4 {
    border-bottom: 1px solid #c4c4c4;
    border-right: none;
    padding: 15px 0;
  }
  .activity-cards {
    padding-bottom: 15px;
  }
}
</style>