<template>
  <div class="library">
   <div class="container">
      <div class="continue-reading" v-if="lastRead">
      <div class="continue-word">
        <h5>You were {{lastRead.type == 'video'?'watching':'viewing'}} {{lastRead.title}}</h5>
        <h6 class="toCaps">{{lastRead.subject}}</h6>

        <div class="continue-reading-icon">
          <div class="continue-btn mr-1">
            <div class>
              <b-avatar icon="eye" variant="success" size="1.2rem"></b-avatar>
            </div>
          </div>
          <p
            class="cpointer"
            @click="view(lastRead.id,lastRead.subject,lastRead.cover)"
          >Continue {{lastRead.type == 'video'?'watching':'viewing'}}</p>
        </div>
      </div>
      <div class="continue-img">
        <img :src="lastRead.cover" alt />
      </div>
    </div>
    <div class="continue-reading" v-else>
      <div class="continue-word">
        <h5>You haven't started any subject yet, Start one now !</h5>
      </div>
    </div>
   </div>

    <div class="container">
      <div class="library-content bg-white p-3 py-4">
        <h4>My Library</h4>
        <div class="search-input text-right">
          <b-input-group class="w-50">
            <b-form-input size="sm" v-model="search" placeholder="Search"></b-form-input>
            <b-input-group-append>
              <div class="create-btn">
                <div class="btn btn-create btn-sm">Search</div>
              </div>
            </b-input-group-append>
          </b-input-group>
        </div>
        <div class="filter-view">
          <div class="filter-table">
            <div class="filter-container">
              <div class="filter-btn btn" @click="toggleFilter">
                <span>Filter</span>
                <i class="icon-sort"></i>
              </div>
            </div>
            <b-navbar toggleable="lg" type="dark" variant="info" v-if="filterShow">
              <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

              <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                  <b-nav-item href="#">Sort By:</b-nav-item>
                </b-navbar-nav>

                <b-navbar-nav class="mx-auto">
                  <b-form-select class="mr-3" v-model="subject">
                    <b-form-select-option value disabled>-- Subjects --</b-form-select-option>
                    <b-form-select-option value>-- All --</b-form-select-option>
                    <b-form-select-option
                      :value="item.toLowerCase()"
                      v-for="(item,idx) in subjects"
                      :key="idx"
                    >{{item}}</b-form-select-option>
                  </b-form-select>
                </b-navbar-nav>
              </b-collapse>
            </b-navbar>
          </div>
          <!-- <router-link to>View all</router-link> -->
        </div>

        <div class="bd-table library assess">
          <b-tabs card content-class="mt-3 ">
            <b-tab title="Home View" title-link-class="mylibrary" active>
              <b-container class="books" title-link-class="mylibrary">
                <b-row>
                  <b-col md="6" lg="3" v-for="(item,idx) in sorted" :key="idx">
                    <b-card
                      :title="item.subject"
                      :img-src="item.cover"
                      img-alt="Image"
                      img-top
                      tag="article"
                      class="mb-2 toCaps"
                    >
                      <b-card-text class="excerpt">{{item.excerpt}}</b-card-text>
                      <div class="progress-resource text-center">
                        <vue-circle
                          :progress="Number(item.progress)"
                          :size="80"
                          :reverse="false"
                          line-cap="round"
                          empty-fill="rgba(0, 0, 0, .1)"
                          :animation-start-value="0.0"
                          :start-angle="0"
                          insert-mode="append"
                          :thickness="3"
                          :show-percent="true"
                          @vue-circle-progress="progress"
                          @vue-circle-end="progress_end"
                        >
                          <p>Complete</p>
                        </vue-circle>
                      </div>
                      <b-button
                        href="#"
                        variant="darkgreen"
                        block
                        size="sm"
                        @click="view(item.id,item.subject,item.cover)"
                      >View</b-button>
                    </b-card>
                  </b-col>
                </b-row>
              </b-container>
            </b-tab>
            <b-tab title="Table View" class="mylibrary">
              <b-table-simple class="table table-bordered table" responsive>
                <b-thead class="thead-light">
                  <b-tr>
                    <b-th>S/n</b-th>
                    <b-th>Subject</b-th>
                    <!-- <th>Title</th> -->
                    <b-th>Excerpts</b-th>
                    <b-th></b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(item,idx) in library" :key="idx">
                    <b-td scope="row">{{idx+1}}</b-td>
                    <b-td>{{item.subject}}</b-td>
                    <!-- <td>{{item.title}}</td> -->
                    <b-td>{{item.excerpt ? item.excerpt:'-'}}</b-td>
                    <b-td class="options">
                      <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                      <div class="option shadow">
                        <ul>
                          <li @click="view(item.id,item.subject,item.cover)">View</li>
                          <li>Drop</li>
                        </ul>
                      </div>
                    </b-td>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
            </b-tab>
          </b-tabs>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueCircle from "vue2-circle-progress";
export default {
  props: ["student"],
  data() {
    return {
      library: [],
      row_number: 0,
      total_library: 0,
      search: "",
      item: false,
      items: [],
      last_page: "",
      first_page: "",
      next_page: "",
      prev_page: "",
      current_page: 1,
      all: false,
      filterShow: false,
      subjects: [],
      subject: "",
      lastRead: {},
    };
  },
  components: {
    VueCircle,
  },
  mounted() {
    this.getLibrary();
    this.getLastRead();
  },
  watch: {
    all: "getAll",
  },
  computed: {
    sorted() {
      return this.library.filter((item) => {
        if (this.subject !== "") {
          return (item.subject = this.subject);
        }
        if (this.search !== "") {
          return item.subject.toLowerCase().includes(this.search.toLowerCase());
        }
        return item;
      });
    },
  },
  methods: {
    progress(event, progress, stepValue) {},
    progress_end(event) {},
    getLastRead() {
      axios
        .get("/api/resource-last", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.lastRead = res.data;
          }
        });
    },
    toggleFilter() {
      this.filterShow = !this.filterShow;
    },
    getLibrary() {
      axios
        .get("/api/library", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.next_page = res.data.next_page_url;
            this.prev_page = res.data.prev_page_url;
            this.current_page = res.data.current_page;
            this.row_number = res.data.per_page;
            this.total_library = res.data.total;
            this.library = res.data.data;

            res.data.data.forEach((item) => {
              if (!this.subjects.includes(item.subject)) {
                this.subjects.push(item.subject);
              }
            });
          }
        });
    },
    getAll() {
      if (!this.all) {
        this.getLibrary();
      } else {
        this.library = [];
        axios
          .get(`/api/all-library`, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.library = res.data;
            }
          });
      }
    },
    firstPage() {
      axios
        .get(this.first_page, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.next_page = res.data.next_page_url;
          this.prev_page = res.data.prev_page_url;
          this.current_page = res.data.current_page;
          this.library = res.data.data;
        });
    },
    lastPage() {
      axios
        .get(this.last_page, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.next_page = res.data.next_page_url;
          this.prev_page = res.data.prev_page_url;
          this.current_page = res.data.current_page;
          this.library = res.data.data;
        });
    },
    next() {
      if (this.next_page) {
        axios
          .get(this.next_page, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            this.next_page = res.data.next_page_url;
            this.prev_page = res.data.prev_page_url;
            this.current_page = res.data.current_page;
            this.library = res.data.data;
          });
      }
    },
    prev() {
      if (this.prev_page) {
        axios
          .get(this.prev_page, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            this.next_page = res.data.next_page_url;
            this.prev_page = res.data.prev_page_url;
            this.current_page = res.data.current_page;
            this.library = res.data.data;
          });
      }
    },
    view(id, subject, cover) {
      let read = {
        id: id,
        cover: cover,
        subject: subject,
      };
      localStorage.setItem("lastRead", JSON.stringify(read));
      this.$router.push(`/student/study/${id}`);
    },
  },
};
</script>

<style scoped>
.continue-reading {
  background-color: #fff;
  padding: 30px;
  display: flex;
  justify-content: space-between;
  margin: 15px 0;
}
.continue-word {
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.continue-word p:first-child {
  font-weight: 500;
  font-size: 30px;
}
.continue-word h6 {
  font-weight: bold;
  font-size: 30px;
}
.continue-reading-icon {
  display: flex;
  align-items: center;
}
.btn-continue {
  background-color: #13a699;
  border-radius: 50%;
  color: #fff;
  padding: 0 6px;
}
.btn-continue i {
  font-size: 22px;
}

.continue-reading-icon p {
  padding-left: 5px;
  margin-bottom: 0;
}
.continue-img img {
  width: 150px;
  height: 150px;
}
.filter-view {
  display: flex;
  justify-content: space-between;
  margin: 10px 0px;
}
.filter-view a {
  color: #000 !important;
  padding-top: 10px;
  font-family: "Montserrat";
}
.filter-view a:hover {
  color: #13a699 !important;
  text-decoration: underline !important;
}
.resource-img img {
  width: 200px;
  height: 150px;
}
.col-md-3 a {
  color: #000;
}
.books {
  padding: 0;
  padding-top: 20px;
}
.search {
  width: 250px;
  border-color: #41cee2;
}

.rounded-pill {
  border-radius: 50rem !important;
}
.filter-container {
  display: flex;
  justify-content: flex-start;
  padding-top: 10px;
}
.filter-btn {
  background: #f7f8fa;
  padding: 5px 20px;
  border-radius: 5px;
}
.filter-btn span {
  font-family: "Montserrat";
  font-weight: bold;
}
.filter-btn i {
  padding-left: 3px;
}
.card-img, .card-img-top {
    
    height: 130px;
    object-fit:cover;
}
.sort-section {
  display: flex;
  justify-content: space-between;
  background: #fff;
  border-radius: 10px;
}
.bg-info {
  background: #fff !important;
  box-shadow: 5px 4px 13px rgba(249, 247, 240, 0.25);
  margin-top: 20px;
  border-radius: 10px;
}

.nav-link {
  color: #000 !important;
}
nav {
  background: rgba(19, 166, 153, 0.9);
  color: white;
}
.btn-create {
  background: rgba(19, 166, 153, 0.9) !important;
  color: #fff;
}
.table .thead-light th {
  color: white;
  background-color: rgba(19, 166, 153, 0.9);
  border-color: rgba(19, 166, 153, 0.9);
}
.bar {
  position: relative;
  width: 20%;
  text-align: center;
  font-size: 14px;
}
.red {
  color: var(--red, red);
}
.search_bar {
  position: relative;
}
.search_input {
  border-color: #f7f8fa;
  height: 30px;
  font-size: 13px;
}
.btn-darkgreen {
  background: rgba(19, 166, 153, 0.9);
  color: #fff !important;
}
.btn-outline-darkgreen {
  color: rgba(19, 166, 153, 0.9) !important;
  border: 1px solid rgba(19, 166, 153, 0.9);
  background: transparent;
}
.form-control::placeholder {
  font-size: 13px;
}
.fa-search {
  position: absolute;
  font-size: 12px;
  right: 14px;
  top: 50%;
  margin-top: -6px;
  color: rgba(0, 0, 0, 0.44);
}
.options {
  width: 15px;
}
td {
  text-transform: capitalize;
}
ul {
  list-style: none;
}
.options {
  position: relative;
}
.option {
  display: none;
  position: absolute;
  padding: 15px;
  bottom: 80%;
  width: 100px;
  right: 12px;
  background: white;
}
.options:hover .option {
  display: block;
}
.option li {
  padding: 4px 15px;
  cursor: pointer;
}
.excerpt {
  height: 42px;
  font-size: 13px;
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 2;
  -moz-line-clamp: 2;
  -ms-line-clamp: 2;
  -o-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  -ms-box-orient: vertical;
  -o-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
}
.search-input {
  display: flex;
  justify-content: flex-end;
}
h4.card-title {
  font-size: 13px;
  text-transform: capitalize;
}
p,
h4 {
  margin-bottom: 3px;
  font-size: 12px;
}
.card-body {
  padding: 10px 10px;
}
@media screen and (max-width: 900px) {
  .continue-img {
    display: none;
  }
  .w-50 {
    width: 75% !important;
}
}
</style>