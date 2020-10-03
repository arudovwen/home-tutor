<template>
  <div class="container">
    <Announcement :student="student" :show="true" class="mt-2" />
    <div class="welcome-board">
      <img
        src="/images/clip-99.png"
        alt
        style="z-index: 10"
        class="d-none d-lg-block"
      />
      <div class="welcome-board-content ml-auto">
        <h3>
          Welcome OnBoard
          <span>{{ student.name }}</span>
        </h3>
        <p>
          Get quick access to your assesment and grade book on the dashboard
          <br />Gain fast knowlege add resources to your library and more
        </p>
      </div>
    </div>

    <b-container fluid>
      <b-row>
        <b-col md="4">
          <div class="result-board">
            <div class="latest-result-board cards">
              <div class="row latest-result-top">
                <div class="col-md-12">
                  <h5>Latest Result</h5>
                </div>
              </div>
               <div class="skelenton" v-if="loader_1">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="95%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
            <div v-else>
                <div class="m-res" v-if="ass_result.length">
                <div
                  class="result-progress border-bottom"
                  v-for="(result, idx) in ass_result"
                  :key="idx"
                >
                  <div>
                    <router-link to="/student/assessment">
                      <h6 class="check">{{ result.subject }}</h6></router-link
                    >
                    <div class="progress-top">
                      <router-link to="/student/assessment">
                        <p class="text-dark">{{ result.title }}</p></router-link
                      >
                      <p>{{ result.total_score }}/{{ result.overall }}</p>
                    </div>
                  </div>
                  <b-progress :max="result.overall" class="mb-3">
                    <b-progress-bar
                      :value="result.total_score"
                      variant="info"
                      :label="`${result.total_score}/${result.overall}`"
                    ></b-progress-bar>
                  </b-progress>
                </div>
              </div>
              <b-alert show v-else class="mt-5">No result available</b-alert>
            </div>
            </div>
          </div>
        </b-col>
        <b-col md="4">
          <div class="resources-update cards">
            <h5>Updates</h5>
             <div class="skelenton" v-if="loader_2">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                <b-skeleton animation="wave" width="95%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
           <div v-else>
              <div class="m-res" v-if="notifications.length">
              <div
                class="resources-inner border-bottom"
                v-for="(resource, idx) in notifications"
                :key="idx"
              >
                <div class="update-content">
                  <div>
                    <!-- <b-avatar icon="asterisk" variant="success" size="sm"></b-avatar> -->
                  </div>
                  <p>{{ resource.message }}</p>
                </div>
                <div class="log-link">
                  <div class="check_it cpointer" @click="gotoHer(resource)">
                    <span class="mr-1">Check it</span>
                    <!-- <b-icon icon="arrow-right"></b-icon> -->
                  </div>
                </div>
              </div>
            </div>
            <b-alert show class="mt-5" v-else>No update available</b-alert>
           </div>
          </div>
        </b-col>

        <b-col md="4">
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
                <b-skeleton animation="wave" width="95%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
              </div>
           <div v-else>
              <div class="class_section" v-if="myto.length">
              <div
                class="class-content p-2"
                v-for="(item, idx) in myto"
                :key="idx"
              >
                <div
                  class="border-bottom"
                  v-if="
                    (getSecond(today) > getSecond(item.start) &&
                      getSecond(today) < getSecond(item.end)) ||
                    getSecond(today) < getSecond(item.start)"
                >
                  <div>
                    <p class="toCaps">
                      {{ item.subject }} Class By {{ item.start | format }}
                    </p>
                    <small>Class ends {{ item.end | format }}</small>
                  </div>

                  <div class="class-content-main">
                    <p>
                      <span>
                        Tutor:
                        <strong class="toCaps">{{ item.tutor }}</strong>
                      </span>
                    </p>

                    <p
                      v-if="
                        getSecond(today) > getSecond(item.start) &&
                        getSecond(today) < getSecond(item.end)
                      "
                      class="green"
                    >
                      Ongoing
                    </p>
                    <p
                      v-if="getSecond(today) < getSecond(item.start)"
                      class="amber"
                    >
                      Upcoming
                    </p>
                  </div>
                </div>
                <!-- <div v-else>
                  <div
                    class="border-bottom"
                    v-if="new Date(item.end).getTime() > new Date().getTime()"
                  >{{item.subject}} {{item.type}} submission deadline {{item.end | moment('h:mm A')}}</div>
                </div> -->
              </div>
            </div>

            <b-alert v-else show class="mt-5 text-align"
              >No Class Available</b-alert
            >

           </div>
            <div class="log-link" v-if="myto.length">
              <router-link to="/student/activities">View all</router-link>
            </div>
          </div>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6">
          <div class="discussion cards">
            <h5>Discussion Board</h5>
              <div class="skelenton" v-if="loader_4">
               <b-skeleton-img></b-skeleton-img> <b-skeleton animation="wave"></b-skeleton>
              
              </div>
           <div v-else>
              <div class="main-note" v-if="groups.length">
              <div
                class="discussion-content"
                v-for="(group, idx) in groups"
                :key="idx"
              >
                <div class="discussion-content-top">
                  <router-link to="/student/groups">
                    <p>
                      <strong class="toCaps check">{{ group.name }}</strong>
                    </p>
                  </router-link>

                  <b-avatar-group size="2.5rem">
                    <b-avatar></b-avatar>

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
                  <p>
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
            <b-alert v-else show class="mt-5 text-center"
              >No discussion available</b-alert
            >
           </div>
          </div>
        </b-col>
        <b-col md="6">
          <div class="notes cards">
            <div class="notes-top">
              <h5>My Notes</h5>
              <div class="btn" v-b-modal.note>
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>ADD</span>
              </div>
            </div>
             <div class="skelenton" v-if="loader_5">
                <b-skeleton-img animation="wave" ></b-skeleton-img>
               
              </div>
            <div v-else>
               <div class="main-note" v-if="notes.length">
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
            <b-alert v-else show class="mt-5 text-center">No note yet</b-alert>
            </div>
            <div class="log-link" v-if="notes.length">
              <div v-b-modal.all-note class="check_it">
                View All
                <b-icon icon="arrow-right"></b-icon>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
    <div>
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
            <b-button variant="darkgreen" type="submit">Add note</b-button>
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
      </b-modal>
    </div>
  </div>
</template>


<script>
import Announcement from "../announcement";
export default {
  props: ["student", "notifications", "groups"],
  data() {
    return {
      groupMessage: [],
      ass_result: [],
      tutors: [],
      new_resource: [],
      students: [],
      classes: [],
      syllabus: [],
      curriculum: [],
      fields: ["class", "subject"],
      field: ["class"],
      value: null,
      animate: true,
      max: null,
      note: {
        title: "",
        description: "",
      },
      assess: [],
      notes: [],
      todaysClass: [],
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
        loader_1:true,
        loader_2:true,
        loader_3:true,
        loader_4:true,
        loader_5:true
    };
  },

  created() {
    this.getNotes();
    this.getTodayClass();
    this.getAss();
    this.getallmessages();
    this.getData();
  },
  components: {
    Announcement,
  },
  computed: {
    newresource() {
      return this.$props.notifications.filter((item) => {
        return item.type == "new resource";
      });
    },
    schedule() {
      return this.todaysClass;
    },
    myto() {
      return this.todaysClass.filter((item) => {
        return (
          (this.getSecond(this.today) > this.getSecond(item.start) &&
            this.getSecond(this.today) < this.getSecond(item.end)) ||
          this.getSecond(this.today) < this.getSecond(item.start)
        );
      });
    },
  },
  methods: {
    getData() {
      let student = JSON.parse(localStorage.getItem("typeStudent"));
      axios
        .get(`/api/student-assessments/${student.level}`, {
          headers: {
            Authorization: `Bearer ${student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.assess = res.data;
             this.loader_2 = false
          }
        })
        .catch((err) => {
          err.response.data;
          console.log(" err.response.data", err.response.data);
        });
    },
    getAss() {
      axios
        .get(`/api/get-assessment-result`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.ass_result = res.data;
           this.loader_1 = false
        });
    },
    getallmessages() {
      axios
        .get(`/api/allmessages`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
         if (res.status == 200) {
            this.groupMessage = res.data;
          this.loader_4 = false
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
        .get(`/api/todays-class/${this.$props.student.sub_class}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
              this.loader_3 = false
            if (res.data.length) {
              // this.todaysClass = res.data[0].courses.map((item) => {
              //   item.type = "times";
              //   return item;
              // });
              this.todaysClass = res.data[0].courses;
            
            }
          }
        });
    },
    gotoHer(id) {
      if (id.type.toLowerCase() == "assessment") {
        this.$router.push(`/student/assessment`);
      }
      if (id.type.toLowerCase() == "group") {
        this.$router.push(`/student/groups`);
      }
      if (id.type.toLowerCase() == "resource") {
        this.$router.push(`/student/resource/view/${id.id}`);
      }
    },
    addNote() {
      let data = {
        title: this.note.title,
        description: this.note.description,
      };
      axios
        .post("/api/note", data, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
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
        .get(`/api/note`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.notes = res.data;
            this.loader_5 = false
          }
        });
    },
    remove(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/note/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
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
  },
};
</script>

<style scoped>
.container {
  font-family: "Montserrat";
  padding-bottom: 50px;
}
.skelenton {
  display: grid;
  height: 100%;
}
.note-body {
  display: flex;
  border-bottom: 1px solid #ccc;
  margin-bottom: 14px;
}
.m-res {
  height: 320px;
  overflow: auto;
}
.main-note {
  height: 290px;
  overflow: auto;
}
.welcome-board {
  background: rgba(19, 166, 153, 0.43);
  width: 100%;
  height: 200px;
  border-radius: 10px;
  display: flex;
  position: relative;
  margin-top: 2rem;
}
.welcome-board img {
  width: 320px;
  height: 280px;
  position: absolute;
  transform: translateY(-28%);
}
.welcome-board-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 2.5rem;
}
.welcome-board-content p {
  text-align: center;
  padding: 10px;
}

.welcome-board-content h3 {
  color: #13a699;
}

.cards {
  margin-top: 2rem;
  background: #fff;
  padding: 1.5rem;
  border-radius: 10px;
  height: 400px;
}
.class_section {
  height: 300px;
  overflow: auto;
}
.result-progress {
  margin-top: 10px;
}
.progress-top {
  display: flex;
  justify-content: space-between;
}

.update-content {
  display: flex;
  justify-content: flex-start;
}
.update-content p {
  font-size: 14px;
  padding-left: 10px;
  margin-bottom: 0;
}
.log-link {
  display: flex;
  justify-content: flex-end;
}
.log-link a {
  color: #808080;
}
.log-link a:hover {
  color: #13a699;
  text-decoration: underline !important;
}
.resources-inner {
  border-bottom: 1px solid #e4e4e4;
  padding-top: 5px;
  padding-right: 15px;
}
.resources-inner:last-child {
  border-bottom: none;
}
.class-content {
  padding-bottom: 10px;
}
.class-content-top {
  display: flex;
  justify-content: space-between;
}

.red {
  color: #ff0000;
}
.green {
  color: green;
}
p {
  font-size: 14px;
}
.class-content-main {
  display: flex;
  justify-content: space-between;
}
.class-content-main p {
  font-size: 12px;
}
.notes-top {
  display: flex;
  justify-content: space-between;
}
.btn {
  background: transparent;
  border: 1px solid #13a699;
  border-radius: 5px;
  color: #13a699;
  font-size: 15px;
}
.btn:hover {
  background: #13a699;
  border-radius: 5px;
  color: #fff;
}
.update-avatar img {
  height: 40px;
  width: 40px;
}
.notes-content {
  border-bottom: 1px solid #e4e4e4;
}
.notes-content:last-child {
  border-bottom: none;
}
.notes-top {
  display: flex;
  justify-content: space-between;
  padding-bottom: 1rem;
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
  color: #13a699;
  background: rgba(19, 166, 153, 0.43);
  border-radius: 50%;
  padding: 9px;
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
  color: #13a699;
}
.check_it {
  color: #13a699;
  font-size: 12px;
}
.check {
  color: #13a699;
}
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  background: none;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #c4c4c4;
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
@media screen and (max-width: 900px) {
  .welcome-board {
    position: initial;
  }
  .welcome-board-content {
    display: initial;
    flex: initial;
    flex-direction: initial;
    text-align: center;
    padding: 10px;
  }
}
</style>