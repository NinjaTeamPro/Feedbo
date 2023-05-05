<template>
  <div class="container">
    <div class="home-header">
      <div class="project-name">
        <router-link :to="{ name: 'Home' }" class="FeedboProject">
          <img :src="logoLink" alt="Feedbo Logo" class="feedbo-main-logo" />
          <span class="Feedbo">
            Feedbo
          </span>
        </router-link>
      </div>
      <AccountView class="fb-account-view" />
    </div>
    <div class="content">
      <div class="wrap-content">
        <h1 class="Feedback-manager-software-and-roadmap-tool">
          Feedback manager software and roadmap tool
        </h1>
      </div>

      <div class="input">
        <div class="input-content">
          <router-link :to="{ name: 'NewBoard' }" class="get-button">
            <AButton type="primary" class="button">
              Create New Board <AIcon type="arrow-right" />
            </AButton>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AccountView from "@/components/Account/AccountView.vue";
export default {
  components: {
    AccountView,
  },
  mounted() {
    document.title = window.bigNinjaVoteWpdata.siteName + " | Feedback Manager";
    var link =
      document.querySelector("link[rel*='icon']") ||
      document.createElement("link");
    link.type = "image/x-icon";
    link.rel = "shortcut icon";
    link.href =
      window.bigNinjaVoteWpdata.pluginUrl + "assets/img/feedbo-logo-square.png";
    document.getElementsByTagName("head")[0].appendChild(link);
  },
  data() {
    return {
      logoLink:
        window.bigNinjaVoteWpdata.pluginUrl +
        "assets/img/feedbo-logo-square.png",
    };
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  * {
    box-sizing: border-box;
    margin: 0px;
    padding: 0px;
  }
  a {
    color: inherit;
    cursor: pointer;
    text-decoration: none;
  }
  .container {
    background: #f8fafb;
    height: 100vh;
  }
  .home-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    letter-spacing: 1px;
    padding: 20px 15px;
  }
  .feedbo-main-logo {
    height: 50px;
    width: 50px;
  }
  .content {
    padding: 30vh 1rem 0px;
  }
  .FeedboProject {
    display: flex;
    align-items: center;
    font-size: 24px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #1a2a37;
  }
  .Feedbo {
    padding-left: 10px;
  }
  .wrap-content {
    width: 100%;
    max-width: 690px;
    margin: 0px auto;
    transition: max-width 0.5s ease 0s;
  }
  .input {
    text-align: center;
    margin: 40px auto;
  }
  .input-content {
    height: 50px;
  }
  .button {
    width: 230px;
    height: 50px;
    border-radius: 5px;
  }
  .get-button {
    color: inherit;
    width: 230px;
    height: 50px;
    border-radius: 5px;
  }
  .Feedback-manager-software-and-roadmap-tool {
    margin: auto;
    width: 535px;
    height: 90px;
    font-size: 35px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.29;
    letter-spacing: normal;
    color: #1a2a37;
    text-align: center;
  }
  @media only screen and (max-width: 600px) {
    .feedbo-main-logo {
      height: 42px;
      width: 42px;
    }
    .FeedboProject {
      font-size: 22px;
    }
    .Feedback-manager-software-and-roadmap-tool {
      width: 90%;
    }
    .content {
      padding-top: 15vh;
    }
    .input {
      margin: 100px auto;
    }
  }
}
</style>
