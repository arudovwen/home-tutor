<template>
  <div class="view-resource">
    <div class="view-resource-header mx-auto">
      <!--please let this be Module-->
      <h2 class="toCaps">{{subject}}</h2>
      <!-- <h6 class="toCaps">{{subject}}</h6> -->
      <!--please change to Class-->
      <p>{{syllabus.grade_level}}</p>
      <!-- <p class="subject-description">{{excerpt}}</p> -->

      <p>
        Created by
        <strong>{{admin.name}}</strong>
      </p>
      <p>Last Updated {{time | moment('MMM D')}}</p>
      <div class="resource-btn mb-3">
        <b-button variant="outline-white">
          <i class="fa fa-share-alt"></i> Share
        </b-button>
      </div>
    </div>
    <div class="resource-content">
      <div class="resource-title"></div>
      <div class="resource-overview">
        <h5>Overview</h5>
        <p class="subject-description">{{syllabus.description}}</p>
      </div>

      <b-row class="mb-5">
        <b-col>
          <h5>What you will learn</h5>

          <b-list-group>
            <b-list-group-item
              v-for="(item,idx) in curriculum.learner_outcome"
              :key="idx"
            >{{item.name}}</b-list-group-item>
          </b-list-group>
        </b-col>
      </b-row>

      <b-row class="mb-5">
        <b-col>
          <h5>Requirements</h5>
          <b-list-group>
            <b-list-group-item>
              <ul class="p-3">
                <li>You need to have completed last term topic on mathematics</li>
                <li>You will understand the basis of all topic attached to the subject</li>
              </ul>
            </b-list-group-item>
          </b-list-group>
        </b-col>
      </b-row>

      <div class="course-course" v-if="modules.length">
        <b-row class="mb-5">
          <b-col>
            <h5 class="text-dark">Course Modules</h5>

            <small>{{modules.length}} sections • {{ totalVideos() + totalDocs() + totalAudio() }} lectures • {{totalMedia() | time}} total length</small>
            <div role="tablist" v-for="(item,idx) in modules" :key="idx">
              <b-card no-body class>
                <b-card-header
                  header-tag="header"
                  class="p-1 text-left d-flex"
                  role="tab"
                
                >
                  <div
                    block
                    v-b-toggle="item.name.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                    variant="secondary"
                    class="text-left d-flex justify-content-between align-items-center w-100"
                  >
                    <span>
                      <b-icon
                        icon="chevron-up"
                        class="mr-2 course-open"
                        style="width: 13px; height: 13px;"
                      ></b-icon>
                      <b-icon
                        icon="chevron-down"
                        class="mr-2 course-close"
                        style="width: 13px; height: 13px;"
                      ></b-icon>
                      {{item.name}}
                    </span>
                    <small
                      class="ml-auto"
                      v-if="getModuleDuration(JSON.parse(item.course))>0"
                    >{{getModuleDuration(JSON.parse(item.course)) | time}}</small>
                  </div>
                </b-card-header>
            
                <b-collapse
                  :id="item.name.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                  accordion="my-accordion"
                  role="tabpanel"
                >
                  <b-card-body>
                   
                    <b-card-text>{{item.excerpt}}</b-card-text>
                    <b-card-text>
                      <div class="mod">
                         <ul v-if="JSON.parse(item.course).length">
                          <li v-for="(course,index) in JSON.parse(item.course)" :key="index">
                            <span
                              @click="handleToggle(course.title.replace(/[^a-z0-9]/gi, '').replace(/\$/g, ''))"
                              class="d-flex justify-content-between align-items-center"
                            >
                              <div>
                                <span class="ml-3">
                                  <i
                                    class="fa fa-play-circle"
                                    v-if="course.type=='video'"
                                    aria-hidden="true"
                                  ></i>
                                  <i
                                    class="fa fa-file-pdf-o"
                                    v-if="course.type=='pdf'"
                                    aria-hidden="true"
                                  ></i>
                                  <i
                                    class="fa fa-volume-up"
                                    v-if="course.type=='audio'"
                                    aria-hidden="true"
                                  ></i>
                                  <i
                                    class="fa fa-file-powerpoint-o"
                                    v-if="course.type=='ppt'"
                                    aria-hidden="true"
                                  ></i>
                                  <i class="fas fa-file-csv" v-if="course.type=='csv'"></i>
                                </span>
                                {{course.title}}
                              </div>
                              <small
                                v-if="(course.type=='video' || course.type=='audio') && course.duration"
                              >{{course.duration | time }}</small>
                              <small
                                v-if="(course.type=='video' || course.type=='audio') && !course.duration"
                              >-:-</small>
                            </span>

                            <b-modal
                              :id="course.title.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                              :title="course.title"
                              hide-footer
                            >
                              <p class="my-4">{{course.overview}}</p>
                            </b-modal>
                          </li>
                        </ul>
                          <p v-else >{{item.description}}</p>
                      </div>
                    </b-card-text>
                  </b-card-body>
                </b-collapse>
              </b-card>
            </div>
          </b-col>
        </b-row>
      </div>
      <b-row class="mb-5">
        <b-col>
          <h5>Frequently Asked Questions</h5>
          <div role="tablist" v-for="(item,idx) in syllabus.faqs" :key="idx">
            <b-card no-body class="mb-1">
              <b-card-header header-tag="header" class="p-1" role="tab">
                <b-button
                  block
                  v-b-toggle="item.question.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                  variant="secondary"
                  class="text-left"
                >{{item.question}}</b-button>
              </b-card-header>
              <b-collapse
                :id="item.question.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                accordion="my-accordion"
                role="tabpanel"
              >
                <b-card-body>
                  <b-card-text>{{item.answer}}</b-card-text>
                </b-card-body>
              </b-collapse>
            </b-card>
          </div>
        </b-col>
      </b-row>

      <div class="admins-note">
        <h5>admins Note</h5>
        <p>You included no notes to this resource</p>
      </div>
      <div class="student-feedback review">
        <div class="featured-review p-3">
          <h5>Featured Review</h5>
          <div class="active-review">
            <div class="featured-review-top">
              <div class="featured-review-inner-top">
                <b-avatar src="/images/profile-img.jpg"></b-avatar>
                <p class="mb-0">
                  <strong>Nkechi Onuha</strong>
                </p>
              </div>
              <ratings :value="5" :disabled="true"></ratings>
            </div>
            <p>
              I had an easy time learning this course with the materials giving.
              <br />Easy to understand and grasp.
            </p>
          </div>
        </div>

        <div class="p-3 all-review">
          <h5>Reviews</h5>
          <div class="border-bottom" v-for="(item,id) in reviews" :key="id">
            <div class="active-review">
              <div class="featured-review-top">
                <div class="featured-review-inner-top">
                  <b-avatar :src="item.user.profile"></b-avatar>
                  <p class="mb-0">
                    <strong class="toCaps">{{item.user.name}}</strong>
                  </p>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <ratings :value="item.rating" :disabled="true" class="mr-2"></ratings>
                <small>{{item.created_at | moment('MMM DD')}}</small>
              </div>
              <p>{{item.comment}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-col cols="5" v-if="modules.length">
      <b-card
        :title="subject"
        :img-src="cover_image"
        img-alt="Image"
        img-top
        style="width: 22rem;"
        class="mb-2 floating-bar shadow-lg toCaps"
      >
        <b-card-text>
          <strong>This includes:</strong>
        </b-card-text>
        <b-card-text class="border-bottom">
          <b-icon icon="folder-symlink" class="mr-2"></b-icon>
          {{ totalVideos() + totalDocs() + totalAudio() }} downloadable resources
        </b-card-text>
        <b-card-text class="border-bottom" v-if="totalVideos()">
          <b-icon icon="collection-play" class="mr-2"></b-icon>
          {{ totalVideos() }} videos
        </b-card-text>
        <b-card-text class="border-bottom" v-if="totalAudio()">
          <b-icon icon="file-music" class="mr-2"></b-icon>
          {{ totalAudio() }} audios
        </b-card-text>
        <b-card-text class="border-bottom" v-if="totalDocs()">
          <b-icon icon="file-earmark-arrow-down" class="mr-2"></b-icon>
          {{ totalDocs() }} Documents
        </b-card-text>

        <b-button href="#" block variant="secondary">Views : 14</b-button>
      </b-card>
    </b-col>
  </div>
</template>

<script>
import Ratings from "../starRatings";
export default {
  props: ["admin",'id'],
  data() {
    return {
      admins: {},
      syllabus: {},
      curriculum: {},
      content: [],
      title: "",
      excerpt: "",
      time: "",
      
      subject: "",
      cover_image: "",
      show: true,
      reviews: [],
      modules: [],
    };
  },
  mounted() {
    this.getResource();
  },
  components: {
    Ratings,
  },
  computed: {},
  methods: {
    totalVideos() {
      var vid = [];
      this.modules.forEach((element) => {
        if (JSON.parse(element.course).length) {
          JSON.parse(element.course).forEach((item) => {
            if (item.type == "video") {
              vid.push(item);
            }
          });
        }
      });

      return vid.length;
    },
    totalDocs() {
      var doc = [];
      this.modules.forEach((element) => {
        if (JSON.parse(element.course).length) {
          JSON.parse(element.course).forEach((item) => {
            if (
              item.type == "pdf" ||
              item.type == "csv" ||
              item.type == "ppt"
            ) {
              doc.push(item);
            }
          });
        }
      });
      return doc.length;
    },
    totalAudio() {
      var aud = [];
      this.modules.forEach((element) => {
        if (JSON.parse(element.course).length) {
          JSON.parse(element.course).forEach((item) => {
            if (item.type == "audio") {
              aud.push(item);
            }
          });
        }
      });
      return aud.length;
    },
    totalMedia() {
      var media = [];
      this.modules.forEach((element) => {
        JSON.parse(element.course).forEach((item) => {
          if (item.type == "audio" || item.type == "video") {
            if (item.duration) {
              media.push(Number(item.duration));
            }
          }
        });
      });

      return media.reduce((a, b) => {
        return a + b;
      }, 0);
    },
    getModuleDuration(arr) {
      var newArr = [];
      arr.filter((item) => {
        if (item.type == "video" || item.type == "audio") {
          newArr.push(Number(item.duration));
        }
      });

      let sum = newArr.reduce(function (a, b) {
        return a + b;
      }, 0);

      return sum;
    },
    getRating() {
      axios
        .get(`/api/admin-rating/${this.$props.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.reviews = res.data;
          }
        });
    },
    handleToggle(title) {
      this.$bvModal.show(title);
    },
    edit(id) {
      this.$router.push(`/admin/aresource/edit/${id}`);
    },
    getResource() {
      axios
        .get(`/api/admin-resource/${this.$props.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            // this.admins = res.data.data.tutor;
            this.syllabus = res.data.data.syllabus;
            this.curriculum = JSON.parse(res.data.data.curriculum.curriculum);
            this.content = res.data.data.content;
            this.excerpt = res.data.data.description;
            this.title = res.data.data.title;
            this.time = res.data.data.time;
            this.id = res.data.data.id;
            this.cover_image = res.data.data.cover_image;
            this.subject = res.data.data.subject;
            this.show = false;
            this.modules = res.data.data.content;
            this.getRating();
          }
        });
    },
  },
};
</script>

<style scoped>
.jumb {
  width: 100%;
  position: relative;
  background: transparent;
}
.mod ul {
  list-style: none;
}
.mod ul li {
  padding: 10px 20px;
  border-bottom: 1px solid #ccc;
}
.top_n {
  position: relative;

  object-fit: cover;
}
.overlay-n {
  top: 0px;
  left: 0px;
  bottom: 0px;
  right: 0px;
  opacity: 0.85;
  background: #f7f8fa;
  backdrop-filter: blur(2px);
  position: absolute;
}
.main-bar {
  width: 70%;
  margin-right: auto;
}
.floating-bar {
  position: fixed;
  right: 5%;
  top: 20%;
}
.view-resource-header {
  background-image: linear-gradient(
      to bottom,
      rgba(10, 64, 101, 0.84),
      rgba(10, 64, 101, 0.84)
    ),
    url(/images/resource-bg.png);
  color: #fff;
  padding: 1.5rem;
  /* margin-top: 2rem; */
  position: relative;
}
.view-resource-header p {
  font-size: 14px;
  margin-bottom: 0.5rem !important;
}
.featured-review {
  background: #f7f8fa;
}
.featured-review-inner-top {
  display: flex;
  padding: 15px 0;
  align-items: center;
}
.featured-review-top {
  display: flex;
  justify-content: space-between;
}
.featured-review-inner-top p {
  padding-left: 10px;
}
.featured-review-top img {
  height: 20px;
  width: 100px;
}

.review {
  background-color: #fff;
  border-radius: 5px;
  margin-bottom: 10px;
}
.btn {
  background: transparent;
  border: 1px solid #fff;
  color: white !important;
  /* margin-right: 10px; */
  /* margin-bottom: 15px; */
}
.btn:hover {
  background: #fff;
  color: #0a4065 !important;
  transition: 0.3s;
  border: none;
}
.resource-content {
  padding: 20px;
  width: 60%;
}
.resource-overview {
  padding-top: 20px;
}
.resource-media-frame {
  padding-top: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.resource-media-frame img {
  width: 50%;
  height: 300px;
}
.admins-note {
  padding-top: 20px;
}
.student-reviews {
  padding: 20px 0;
}
.btn-secondary {
  color: #333 !important;
}
.btn-primary {
  background: #41cee2 !important;
}
.overlay-n {
  top: 0px;
  left: 0px;
  bottom: 0px;
  right: 0px;
  opacity: 0.85;
  background: #f7f8fa;
  backdrop-filter: blur(2px);
  position: absolute;
}

.shy {
  opacity: 0.4;
}
.view-resource-header p {
  margin-bottom: 0.5rem !important;
}

.collapsed > span > .course-open,
.not-collapsed > span > .course-close {
  display: none;
}

.collapsed > .course-open,
.not-collapsed > .course-close {
  display: none;
}

/* .course-course {
  color: #13a699;
} */
.featured-review {
  background: #f7f8fa;
}
.featured-review-inner-top {
  display: flex;
  padding: 15px 0;
  align-items: center;
}
.featured-review-top {
  display: flex;
  justify-content: space-between;
}
.featured-review-inner-top p {
  padding-left: 10px;
}
.featured-review-top img {
  height: 20px;
  width: 100px;
}

.container {
  width: 60%;
  margin-right: 40%;
  padding: 20px 30px 40px;
}
.course-course {
  margin-top: 2rem;
}
.card {
  border-radius: 0;
}
</style>