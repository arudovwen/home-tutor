<template>
  <div class="landing">
    <Navigation />
    <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active" data-interval="10000">
          <div class="overlay"></div>
          <img src="/images/7.png" class="d-block w-100" alt="..." />
        </div>
        <div class="carousel-item" data-interval="10000">
          <div class="overlay"></div>
          <img src="/images/6.jpg" class="d-block w-100" alt="..." />
        </div>
        <div class="carousel-item">
          <div class="overlay"></div>
          <img src="/images/8.png" class="d-block w-100" alt="..." />
        </div>
      </div>
      <div class="carousel-control">
        <a class href="#carouselExampleInterval" role="button" data-slide="prev">
          <span class="carousel-prev" aria-hidden="true">
            <img src="/images/chevron-prev.png" alt="prev" />
          </span>
          <span class="sr-only">Previous</span>
        </a>
        <a class href="#carouselExampleInterval" role="button" data-slide="next">
          <span class="carousel-next" aria-hidden="true">
            <img src="/images/chevron-next.png" alt="nxt" />
          </span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <div class="carousel-txt">
        <h1>
          EDUCATION IN 
          <br />HAS A NEW FACE
        </h1>

        <router-link to="/school/register">
          <button v-waves.button v-waves.float v-waves.light class="sec_color">
            <span>ENROLL</span>
            <!-- <i class="fa fa-long-arrow-right sec_color" aria-hidden="true"></i> -->
          </button>
        </router-link>
      </div>
    </div>

    <div class="container" id="about">
      <div class="row mid-section">
        <div class="col-md-6 col-sm-12 mid-section-text">
          <div class="text-center heading mb-5">
            <h2 class="mb-0">About Us</h2>
            <img src="/images/after-img.png" class="img-fluid" alt />
          </div>
          <p>
            We recognize that any form of learning that does not leverage technology has no place in today’s world.
            We are excited to rise to the the occasion to ensure that all Imo students can benefit from a safe and self-paced learning experience.
          </p>
          <p>
            <strong> E-LEARNING</strong> is a secure and robust online portal for learning and collaboration. Multimedia learning resources, collaboration, monitoring & control mechanics, and many more extremely useful features all happen in this single shared digital space and can be accessed on the go.
          </p>
        </div>
        <div class="col-md-6 col-sm-12 d-flex align-items-center">
          <img src="/images/mid-section-img.png" class="img-fluid" alt />
        </div>
      </div>
    </div>

    <div class="who-can-section">
      <div class="text-center heading mb-5">
        <h2 class="mb-0">Who Can Use The Service</h2>
        <img src="/images/after-img.png" class="img-fluid" alt />
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-4 who-card-first">
            <div class="card who-card shadow-sm">
              <img src="/images/school-svg.png" class="img-fluid" alt />
              <div class="who-card-inner">
                <b-table-simple style="background:transparent" borderless>
                  <b-thead>
                    <b-tr>
                      <b-th>
                        <h3 class="mb-4">Schools</h3>
                      </b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr>
                      <b-td>Set up custom online school</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Onboard teachers and students</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Monitor all teaching and learning activities</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>
                        <div class="btn-card">
                          <router-link to="/school/register" class="card-btn get-start">Get Started</router-link>
                        </div>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </div>
          <div class="col-md-4 who-card-first">
            <div class="card who-card shadow-sm">
              <img src="/images/tutors-svg.png" class="img-fluid" alt />
              <div class="who-card-inner">
                <b-table-simple style="background:transparent" borderless>
                  <b-thead>
                    <b-tr>
                      <b-th>
                        <h3 class="mb-4">Teachers</h3>
                      </b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr>
                      <b-td>Create and share learning resources</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Administer online live classes</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Monitor all students learning activities</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>
                        <div class="btn-card">
                          <router-link to="/tutor" class="card-btn get-start">Get Started</router-link>
                        </div>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </div>
          <div class="col-md-4 who-card-first">
            <div class="card who-card shadow-sm">
              <img src="/images/school-svg.png" class="img-fluid" alt />
              <div class="who-card-inner">
                <b-table-simple style="background:transparent" borderless>
                  <b-thead>
                    <b-tr>
                      <b-th>
                        <h3 class="mb-4">Students</h3>
                      </b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr>
                      <b-td>Access learning resources</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Complete assignments and assessments</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>Chat with teachers and colleagues</b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>
                        <div class="btn-card">
                          <router-link to="/student" class="card-btn get-start">Get Started</router-link>
                        </div>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="video-section container mb-5" id="sponsor">
      <img src="/images/play-svg.png" alt />
    </div>
    <div class="sponsors">
      <div class="text-center heading mb-5">
        <h2 class="mb-0">Our Sponsors</h2>
        <img src="/images/after-img.png" class="img-fluid" alt />
      </div>

      <div class="container">
        <b-row class="justify-content-center justify-content-sm-between">
          <b-col cols="6" sm="3" md="2" class="text-center">
            <img src="/images/sponsor-img-1.png" class="mb-2 spons" alt />
            <div>
              <p class="spons-text">Imo State Government</p>
            </div>
          </b-col>
          <b-col cols="6" sm="3" md="2" class="text-center">
            <img src="/images/sponsor-img-2.png" class="mb-2 spons" alt />
            <div>
              <p class="spons-text">Federal Government Nigeria</p>
            </div>
          </b-col>
          <b-col cols="6" sm="3" md="2" class="text-center">
            <img src="/images/ubec.jpeg" class="mb-2 spons" alt />
            <div>
              <p class="spons-text">
                Universal Basic Education Commission
                <br />(UBEC)
              </p>
            </div>
          </b-col>
          <b-col cols="6" sm="3" md="2" class="text-center">
            <img src="/images/imsu2.png" class="mb-2 spons" alt />
            <div>
              <p class="spons-text"></p>
            </div>
          </b-col>
          <b-col cols="6" sm="3" md="2" class="text-center">
            <img src="/images/sponsor-img-1.png" class="mb-2 spons" alt />
            <div>
              <p class="spons-text">Imo Ministry of Education</p>
            </div>
          </b-col>
        </b-row>
      </div>
    </div>

    <div class="footer">
      <div class="footer-inner container">
        <div class="d-flex align-items-center">
          <img src="/images/.png" class="d-inline-block align-top logo mr-3" alt="logo" />
          <span class="logo-text"> E-LEARNING</span>
        </div>
        <img src="/images/footer-divider.png" class="img-fluid" alt />
        <div class="footer-end">
          <div class="footer-end-right">
            <img src="/images/phone-ilc.png" alt />
            <p>+239000000000</p>
          </div>

          <div class="footer-end-right">
            <img src="/images/mail-ilc.png" alt />
            <p>imlc@yahoo.com</p>
          </div>
        </div>
      </div>
      <div class="footer-down container">
        <p>Powered by... &nbsp; Coeur COnsulting ()</p>
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "../navigation/navigation";
export default {
  components: {
    Navigation,
  },
};
</script>

<style scoped>
a {
  text-decoration: none;
}

.card {
  background-color: #f7f8fa;
  border: none;
}
.sec_color {
  background-color: var(--primary-btn);
  color: #fff;
  font-size: 18px;
}
.btn-card {
  margin-top: 16px;
}
.card-btn {
  border: 2px solid;
  border-radius: 4px;
  padding: 10px 28px;
}
.spons {
  width: 140px;
  height: 140px;
  filter: grayscale(100%);
}
.logo {
  width: 65px;
  color: #3b4e3a;
  font-weight: bolder;
}
ul {
  list-style: none;
  margin: 0;
}
ul li {
  margin-bottom: 14px;
  font-size: 17px;
}
td {
  font-size: 16.5px;
}
.footer-down {
  color: #dfd291;
}
@media screen and (max-width: 500px) {
  .carousel-txt h1 {
    font-size: 1.2rem;
    font-weight: bold;
  }
  .carousel-prev img {
    width: 40px;
  }
  .carousel-next img {
    width: 40px;
  }
  .text-center img {
    padding: 10px;
  }
  .pricing-card {
    padding-bottom: 20px;
  }

  .footer-inner h5 {
    font-size: 0.8rem;
  }
  .footer-inner p {
    font-size: 14px;
  }
  .footer-end-right img {
    width: 16px;
    height: 16px;
  }
  .footer-end-right {
    margin-right: 20px;
  }
  .landing {
    overflow-x: hidden;
  }
  .spons {
    width: 60px;
    height: 60px;
  }
  .spons-text {
    font-size: 9px;
  }
  .text-center img{
    padding: 0;
  }
  .who-card-first{
    padding:30px;
  }
 
  p{
    font-size:14px;
  }
}
</style>