<template>
  <div class="body">
    <b-modal id="announcement" title="Announcement" hide-footer>
      <b-form @submit.prevent="addAnnouncement">
        <b-form-group>
          <b-form-textarea placeholder="Type here.." v-model="body" max="200"></b-form-textarea>
        </b-form-group>
        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Save</b-button>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal id="update-announcement" title="Update Announcement" hide-footer>
      <b-form @submit.prevent="updateann">
        <b-form-group>
          <b-form-textarea placeholder="Type here.." v-model="body" max="200"></b-form-textarea>
        </b-form-group>
        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Update</b-button>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal id="showall" title="All Announcements" size="lg" hide-footer>
      <b-table responsive sticky-header :fields="ann_fields1" :items="announcement">
        <template v-slot:cell(heading)="data">
          <div>{{data.item.body}}</div>
        </template>
      </b-table>
    </b-modal>
    <b-container fluid class="bv-example-row">
      <b-row>
        <b-col md="4">
          <div class="admin-dashboard-top-cards">
            <b-card>
               <div class="inner-top-cards" v-if="loader">
                <b-skeleton type="avatar"></b-skeleton>
                <div class="inner-top-cards-text pl-2">
                  <p>
                   <b-skeleton width="120px"></b-skeleton>
                  </p>
                  <p>  <b-skeleton width="200px"></b-skeleton></p>
                </div>
              </div>
              <div class="inner-top-cards" v-else>
                <img src="/images/teacher-green.png" class="img-fluid" alt />
                <div class="inner-top-cards-text pl-2">
                  <p>
                    <strong>{{tutors.length}}+</strong>
                  </p>
                  <p>Tutors</p>
                </div>
              </div>
            </b-card>
          </div>
        </b-col>
        <b-col md="4">
          <div class="admin-dashboard-top-cards">
            <b-card>
              <div class="inner-top-cards" v-if="loader_1">
                <b-skeleton type="avatar"></b-skeleton>
                <div class="inner-top-cards-text pl-2">
                  <p>
                   <b-skeleton width="120px"></b-skeleton>
                  </p>
                  <p>  <b-skeleton width="200px"></b-skeleton></p>
                </div>
              </div>
              <div class="inner-top-cards" v-else>
                <img src="/images/teacher-green.png" class="img-fluid" alt />
                <div class="inner-top-cards-text pl-2">
                  <p>
                    <strong>{{students.length}}+</strong>
                  </p>
                  <p>Students</p>
                </div>
              </div>
            </b-card>
          </div>
        </b-col>
        <b-col md="4">
          <div class="admin-dashboard-top-cards">
            <b-card>
              <div class="inner-top-cards" v-if="loader_2">
                <b-skeleton type="avatar"></b-skeleton>
                <div class="inner-top-cards-text pl-2">
                  <p>
                   <b-skeleton width="120px"></b-skeleton>
                  </p>
                  <p>  <b-skeleton width="200px"></b-skeleton></p>
                </div>
              </div>
              <div class="inner-top-cards" v-else>
                <img src="/images/teacher-green.png" class="img-fluid" alt />
                <div class="inner-top-cards-text pl-2">
                  <p>
                    <strong>{{classes.length}}+</strong>
                  </p>
                  <p>{{JSON.parse(admin.level).includes('tertiary')?'Levels':'Classes'}}</p>
                </div>
              </div>
            </b-card>
          </div>
        </b-col>
      </b-row>

      <b-row>
        <b-col cols="12" sm="9">
          <div class="chart">
            <column-chart :messages="{empty: 'No data'}" :xtitle="JSON.parse(admin.level).includes('tertiary')?'Levels':'Classes'" ytitle=" Students" :data="myclass" :colors="colors" id="users-chart"></column-chart>
           
          </div>
        </b-col>

        <b-col cols="12" sm="3">
          <div class="announce">
            <h5>Tutors</h5>
            <small class="d-flex justify-content-around my-2">
              <span>
                <b-icon icon="circle-fill" class="ongoing-i"></b-icon> Online
              </span>
              <span>
                <b-icon icon="circle-fill" class="finished-i"></b-icon> Offline
              </span>
            </small>
             <div class="skelenton" v-if="loader">
                <b-skeleton animation="wave" width="85%"></b-skeleton>
                <b-skeleton animation="wave" width="65%"></b-skeleton>
              
                <b-skeleton animation="wave" width="75%"></b-skeleton>
                <b-skeleton animation="wave" width="45%"></b-skeleton>
                <b-skeleton animation="wave"></b-skeleton>
                
              </div>
            <b-list-group class="online-t" v-else>
              <b-list-group-item
                class="toCaps onlinetutors"
                :id="item.name.replace(' ','')"
                v-for="(item,idx)  in tutors"
                :key="idx"
              
              >
                <b-icon
                  icon="circle-fill"
                  class="mr-2"
                  :class="{'ongoing-i':onlineTutors.includes(item.name),'finished-i':!onlineTutors.includes(item.name)}"
                ></b-icon>
                {{item.name}}
                <b-popover :target="item.name.replace(' ','')" triggers="hover" placement="top">
                  <div v-if="onlineTutors.includes(item.name)"  class="text-center">
                    <p class="mb-0 cpointer onl"   @click="startChat(item.id,item.name)">Start a chat</p>
                  </div>
                   <div v-else class="text-center">                   
                    <p class="mb-0 onl cpointer"   @click="startChat(item.id,item.name)">Send an Offline message</p>
                  </div>
                </b-popover>
              </b-list-group-item>
            </b-list-group>
          </div>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="4" class="activity-cards">
          <div class="ongoing-lesson cards">
            <div class="notes-top">
              <h5>Upcoming Lessons</h5>
            </div>
            <small class="d-flex lesson-indicator justify-content-around">
              <span>
                <b-icon icon="circle-fill" class="upcoming-i mr-2"></b-icon>Upcoming
              </span>
              <span>
                <b-icon icon="circle-fill" class="ongoing-i mr-2"></b-icon> Ongoing
              </span>
              <span>
                <b-icon icon="circle-fill" class="finished-i mr-2"></b-icon>Finished
              </span>
            </small>
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
              <div class="main-note" v-if="lessons.length">
              <div class="ongoing-lesson-content" v-for="(lesson,idx) in lessons" :key="idx">
                <h6 class="toCaps">{{lesson.subject}}</h6>
                <p>by: {{lesson.tutor}}</p>
                <div class="ongoing-lesson-time">
                  <p
                    :class="{ongoing:(getSecond(today)  > getSecond(lesson.start)) && (getSecond(today)   < getSecond(lesson.end)),
               upcoming:(getSecond(today)  <  getSecond(lesson.start)) && (getSecond(today)   < getSecond(lesson.end)),
               finished:(getSecond(today)   >  getSecond(lesson.start)) && (getSecond(today) >getSecond(lesson.end))
               }"
                  >
                    {{lesson.start| format}}
                    <span>{{lesson.level}}</span>
                  </p>
                </div>
              </div>
            </div>
            <b-alert :show="!lessons.length"  class="mt-5">No lesson today</b-alert>
            <div class="lesson-link"  v-if="lessons.length">
              <div v-b-modal.lessons>View Today's Lesson</div>
            </div>
            </div>
          </div>
        </b-col>
        <b-col md="4" class="activity-cards">
          <div class="notes cards">
            <div class="notes-top">
              <h5>Announcements</h5>

              <b-button class="mb-3" variant="secondary-outline" size="sm" @click="addann">
                <b-icon icon="plus-circle" style="width:14px" font-scale="1"></b-icon>
                Add
               
              </b-button>
            </div>
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
              <div class="main-note" v-if="announcement.length">
              <b-table responsive :fields="ann_fields" :items="announcement">
                <template v-slot:cell(heading)="data">
                  <div class="heading">{{data.item.body}}</div>
                </template>
                <template v-slot:cell(status)="data">
                  <div>{{data.item.status?'Active':'Inactive'}}</div>
                </template>
                <template v-slot:cell(action)="data">
                  <div>
                    <b-popover :target="data.item.id.toString()" triggers="hover" placement="top">
                      <ul>
                        <li
                          class="cpointer"
                          v-if="!data.item.status"
                          @click="handleStatus(data.item.id,true)"
                        >Activate</li>
                        <li
                          class="cpointer"
                          v-if="data.item.status"
                          @click="handleStatus(data.item.id,false)"
                        >Deactivate</li>

                        <li class="cpointer" @click="editAnn(data.item.id)">Edit</li>
                        <li class="cpointer" @click="dropAnn(data.item.id)">Drop</li>
                      </ul>
                    </b-popover>
                    <b-icon icon="three-dots-vertical" size="sm" :id="data.item.id.toString()"></b-icon>
                  </div>
                </template>
              </b-table>
            </div>
            <b-alert :show="!announcement.length" class="mt-5">No announcement yet</b-alert>

            <div class="log-link text-right lesson-link" v-if="announcement.length">
              <div @click="showALL">View All</div>
            </div>
           </div>
          </div>
        </b-col>
        <b-col md="4" class="activity-cards">
          <div class="notes cards">
            <div class="notes-top">
              <h5>My Notes</h5>

              <b-button class="mb-3" variant="secondary-outline" size="sm" v-b-modal.note>
                <b-icon icon="plus-circle" style="width:14px" font-scale="1"></b-icon>
                Add
              
              </b-button>
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
              <div class="note-body" v-for="(note,idx) in notes" :key="idx">
                <div class="mr-2">
                  <b-icon icon="stickies-fill" class="my-icon"></b-icon>
                </div>
                <div class="notes-content w-100">
                  <div class="notes-content-top">
                    <h6 class="title mr-3 mb-2">{{note.title}}</h6>
                  </div>
                  <div class="notes-content-main">
                    <p class>{{note.note}}</p>
                    <div class="notes-date d-flex align-items-center justify-content-between w-100">
                      <p>
                        Posted:
                        <strong>{{note.created_at | moment('DD/MM/YYYY')}}</strong>
                      </p>

                      <b-icon icon="trash" class="my-icon cpointer" @click="remove(note.id)"></b-icon>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <b-alert v-else :show="!notes.length" class="mt-5">No note yet</b-alert>

            <div class="log-link text-right lesson-link"  v-if="notes.length">
              <div v-b-modal.all-note>View All</div>
            </div>
           </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
    <b-modal id="note" title="New note" hide-footer>
      <b-form @submit.prevent="addNote">
        <b-form-group class="mb-3">
          <label for>Title</label>
          <b-form-input required v-model="note.title" placeholder="Enter title"></b-form-input>
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
        <div class="note-body" v-for="(note,idx) in notes" :key="idx">
          <div class="mr-2">
            <b-icon icon="stickies-fill" class="my-icon"></b-icon>
          </div>
          <div class="notes-content w-100">
            <div class="notes-content-top">
              <h6 class="title mr-3 mb-2">{{note.title}}</h6>
            </div>
            <div class="notes-content-main">
              <p class>{{note.note}}</p>
              <div class="notes-date d-flex align-items-center justify-content-between w-100">
                <p>
                  Posted:
                  <strong>{{note.created_at | moment('DD/MM/YYYY')}}</strong>
                </p>
                <b-icon icon="trash" class="my-icon cpointer" @click="remove(note.id)"></b-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal id="lessons" title="Todays Lessons" hide-footer>
      <div class v-if="lessons">
        <div class="ongoing-lesson-content" v-for="(lesson,idx) in lessons" :key="idx">
          <h6>{{lesson.subject}}</h6>
          <p>by: {{lesson.tutor}}</p>
          <div class="ongoing-lesson-time">
            <p
              :class="{ongoing:(getSecond(today)  > getSecond(lesson.start)) && (getSecond(today)   < getSecond(lesson.end)),
               upcoming:(getSecond(today)  <  getSecond(lesson.start)) && (getSecond(today)   < getSecond(lesson.end)),
               finished:(getSecond(today)   >  getSecond(lesson.start)) && (getSecond(today) >getSecond(lesson.end))
               }"
            >
              {{lesson.start| format}}
              <span>{{lesson.level}}</span>
            </p>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>


<script>
export default {
  props: ["admin", "onlineTutors"],
  data() {
    return {
      tutors: [],
      students: [],
      classes: [],
      syllabus: [],
      curriculum: [],
      fields: ["class", "subject"],
      field: ["class"],
      announcement: [],
      ann_fields: ["heading", "status", "action"],
      ann_fields1: ["heading"],
      body: "",
      status: false,
      announce_id: null,
      myclass: [],
      note: {
        title: "",
        description: "",
      },
      notes: [],
      lessons: [],
      colors:[],
      loader:true,
      loader_1:true,
      loader_2:true,
      loader_3:true,
      loader_4:true,
      loader_5:true,
      loader_6:true,
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
    };
  },
  created() {
    this.getStudents();
    this.getClasses();
    this.getTutors();
    this.getSyllabus();
    this.getCurriculum();
    this.getAnnouncement();
    this.getNotes();
    this.getClassToday();
  },
  methods: {
    
    startChat(id, name) {
      this.$router.push(`/admin/chat?id=${id}&name=${name}`);
    },
    getSecond(hms) {
      var a = hms.split(":"); // split it at the colons

      // minutes are worth 60 seconds. Hours are worth 60 minutes.
      var seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
      return seconds;
    },
    getClassToday() {
      axios
        .get("/api/class-today", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.lessons = res.data;
               this.loader_3 = false
          }
        });
    },
    showALL() {
      this.$bvModal.show("showall");
    },
    addann() {
      this.$bvModal.show("announcement");
    },
    getAnnouncement() {
      axios
        .get("/api/announcement", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.announcement = res.data;
               this.loader_4 = false
          }
        });
    },
    addAnnouncement() {
      let data = {
        body: this.body,
        status: false,
      };
      axios
        .post("/api/announcement", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.announcement.push(res.data);
            this.body = "";
            this.status = false;
            this.$bvModal.hide("announcement");
          }
        });
    },
    dropAnn(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/announcement/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.announcement = this.announcement.filter(
                (item) => item.id !== id
              );
            }
          });
      }
    },
    editAnn(id) {
      this.announce_id = id;
      axios
        .get(`/api/announcement/${id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.body = res.data.body;
            this.status = res.data.status;
            this.$bvModal.show("update-announcement");
          }
        });
    },
    handleStatus(id, stat) {
      axios
        .get(`/api/announcement/${id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            let data = {
              body: res.data.body,
              status: stat,
            };
            axios
              .put(`/api/announcement/${id}`, data, {
                headers: {
                  Authorization: `Bearer ${this.$props.admin.access_token}`,
                },
              })
              .then((res) => {
                if (res.status == 200) {
                  this.getAnnouncement();
                  this.body = "";
                  this.status = false;
                }
              });
          }
        });
    },
    updateann() {
      let data = {
        body: this.body,
        status: false,
      };

      axios
        .put(`/api/announcement/${this.announce_id}`, data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getAnnouncement();
            this.body = "";
            this.status = false;
            this.$bvModal.hide("update-announcement");

          }
        });
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
               this.loader_1 = false
          }
        });
    },
    getCurriculum() {
      axios
        .get("/api/curriculum", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
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
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
               this.loader = false
          }
        });
    },
    getClasses() {
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.classes = res.data;
            res.data.forEach((item) => {
              this.myclass.push([
                item.class_name,
                this.handleStudents(item.class_name),
                
              ]);
              this.colors.push('#13a699')
            });
               this.loader_2 = false
          }
        });
    },
    handleStudents(val) {
      var stud = this.students.filter((item) =>
        item.student_level.toString().toLowerCase().includes(val.toString().toLowerCase())
      );

      return Number(stud.length) || 0;
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
    addNote() {
      let data = {
        title: this.note.title,
        description: this.note.description,
      };
      axios
        .post("/api/admin-note", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
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
    remove(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/delete-note/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
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
    getNotes() {
      axios
        .get(`/api/admin-note`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.notes = res.data;
            this.loader_5 = false
          }
        });
    },
  },
};
</script>

<style scoped>
.cards {
  background: #fff;
  height: 500px;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 5px 4px 11px rgba(249, 247, 240, 0.25);
}
#users-chart{

}
.ongoing-lesson-content {
  padding-top: 0.8rem;
  border-bottom: 1px solid #e5e5e5;
  font-size:14.5px;
}
.ongoing-lesson-content:last-child {
  border-bottom: none;
}
h6 {
  font-weight: 600;
}
.tab-announce {
  height: 250px;
  overflow: auto;
}
.ongoing-lesson-content p {
  margin-bottom: 0;
}
.ongoing-lesson-time {
  display: flex;
  justify-content: flex-end;
  padding-bottom: 10px;
}
.ongoing-lesson-time p {
  border-radius: 5px;
  padding: 5px;
  font-weight: 500;
}
.online-t {
  height: 280px;
  overflow: auto;
}
.onlinetutors{
  font-size: 14px;
}
.ongoing {
  background: #008e3a;
}
.upcoming {
  background: #ffc200;
}
.finished {
  background: red;
}
.heading {
  height: 17px;
  font-size: 15px;
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 1;
  -moz-line-clamp: 1;
  -ms-line-clamp: 1;
  -o-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  -ms-box-orient: vertical;
  -o-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
}
.lesson-link {
  display: flex;
  justify-content: flex-end;
  padding-top: 10px;
}
.lesson-link div,
.log-link {
  color: #008e3a;
}
.lesson-link a:hover {
  background: #008e3a;
  color: white;
  border-radius: 5px;
  padding: 10px;
}
.notes-top {
  display: flex;
  justify-content: space-between;
  padding-bottom: 1.5rem;
}
ul {
  margin: 0;
  list-style: none;
}
.btn {
  background: transparent;
  border: 1px solid;
  color: #008e3a;
}
.btn:hover {
  background: #008e3a;
  color: #fff;
}
.note-body {
  display: flex;
  border-bottom: 1px solid #ccc;
  margin-bottom: 14px;
}
.onl{
  font-size:13px;
}
.skelenton {
  display: grid;
  height: 80%;
}
.m-res {
  height: 320px;
  overflow: auto;
}
.main-note {
  height: 330px;
  overflow: auto;
}
.announce {
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
.ongoing-i {
  color: #008e3a;
}
.upcoming-i {
  color: #ffc200;
}
.finished-i {
  color: red;
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
.lesson-indicator{
  padding-bottom: 15px;
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
.alert-info {
    color: #008e3a;
    background-color: rgba(0, 142, 58, 0.4);;
    border-color: rgba(0, 142, 58, 0.4);;
}
@media screen and (max-width: 900px){
  .activity-cards{
    padding-bottom: 15px;
  }
}

</style>