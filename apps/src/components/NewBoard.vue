<template>
  <div class="newboard-container">
    <div class="newboard-header">
      <div class="project-name">
        <router-link
          :to="{ name: 'Home' }"
          class="FeedboProject"
        >
          <img
            :src="logoLink"
            alt="Feedbo Logo"
            class="feedbo-main-logo"
          >
          <span class="Feedbo">
            Feedbo
          </span>
        </router-link>
      </div>
      <AccountView class="fb-account-view" />
    </div>
    <div class="content">
      <div class="form-container">
        {{ category.categorys }}
        <h2 class="Create-new-board">
          Create New Board
        </h2>
        <p>Enter your board name. You can change it later.</p>
        <AForm
          id="form"
          :form="form"
          @submit.prevent="handleSubmit()"
        >
          <AFormItem>
            <AInput
              v-decorator="[
                'category',
                {
                  rules: [
                    { required: true, message: 'This is a required field' },
                  ],
                },
              ]"
              placeholder="Enter name project"
              class="input-name-project"
            />
          </AFormItem>
          <AFormItem>
            <AButton
              type="primary"
              html-type="submit"
              class="submit-form-button"
              :loading="category.isLoadingCreateBoard"
            >
              Create <AIcon type="arrow-right" />
            </AButton>
          </AFormItem>
        </AForm>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import AccountView from "@/components/Account/AccountView.vue";
export default {
  components: {
    AccountView,
  },
  data() {
    return {
      form: this.$form.createForm(this, { name: "coordinated" }),
      logoLink:
        window.bigNinjaVoteWpdata.pluginUrl +
        "assets/img/feedbo-logo-square.png",
    };
  },
  methods: {
    handleSubmit() {
      this.form.validateFields((err, values) => {
        if (!err) {
          if (this.user.userAnonymous == true) {
            window.location.href =
              window.bigNinjaVoteWpdata.siteUrl + "/wp-login.php?message=Please sign in to create your own board";
          }
          else {
            const component = this;
            const requestParams = {
              values: values,
              component: component,
            };
            this.$store.dispatch("category/createCategory", requestParams);
          }
        }
      });
    },
  },
  computed: {
    ...mapState([ "category", "user" ]),
  },
  mounted() {
    document.title = window.bigNinjaVoteWpdata.siteName + " | New Board";
    const link =
      document.querySelector("link[rel*='icon']") ||
      document.createElement("link");
    link.type = "image/x-icon";
    link.rel = "shortcut icon";
    link.href =
      window.bigNinjaVoteWpdata.pluginUrl + "assets/img/feedbo-logo-square.png";
    document.getElementsByTagName("head")[0].appendChild(link);
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  div {
    display: block;
  }
  a {
    color: inherit;
    cursor: pointer;
    text-decoration: none;
  }
  .newboard-container {
    background: #27ae60;
    height: 100vh;
  }
  .newboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 15px;
  }
  .feedbo-main-logo {
    height: 50px;
    width: 50px;
  }
  .content {
    padding: 30vh 1rem 0px;
    width: 430px;
    margin: auto;
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
    color: #ffffff;
  }
  .Feedbo {
    padding-left: 10px;
  }
  .button {
    width: 230px;
    height: 50px;
    border-radius: 5px;
  }
  .Create-new-board {
    font-size: 21px;
    font-weight: 600;
    color: #fff;
    line-height: 1.48;
  }
  .content p {
    font-size: 14px;
    line-height: 1.71;
    color: #fff;
  }
  .input-name-project {
    height: 50px;
  }
  .submit-form-button {
    width: 100%;
    height: 50px;
  }
  @media screen and (max-width: 600px) {
    .feedbo-main-logo {
      height: 42px;
      width: 42px;
    }
    .FeedboProject {
      font-size: 22px;
    }
    .form-container {
      width: 90%;
    }
    .content {
      padding-top: 15vh;
    }
  }
}
</style>
