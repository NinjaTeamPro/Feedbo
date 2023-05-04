<template>
  <div class="vote-list-wrap">
    <div class="vote-list-container">
      <div class="voter-title" :style="{ color: category.theme.text }">
        Voters
      </div>
      <div class="voter-list">
        <APopover trigger="click" placement="bottom">
          <template slot="content">
            <div :class="{ 'has-error': !validateEmail(userCreate) }">
              <AInput
                v-model="userCreate"
                type="email"
                class="behalf-user-input feedbo-input"
                placeholder="Vote on behalf of a user..."
              />
            </div>
            <div v-if="!validateEmail(userCreate)">
              <span class="create-user-email-error"
                >Please input correct email</span
              >
            </div>
            <div
              v-if="userCreate != '' && validateEmail(userCreate)"
              class="create-user-behalf"
              @click="createUserBehalf"
            >
              <AIcon type="plus" />
              <span>
                Create
                <span class="user-name-create">
                  {{ userCreate }}
                </span>
              </span>
            </div>
            <PerfectScrollbar
              v-if="userCreate == ''"
              class="list-vote-scrollbar"
            >
              <div
                v-for="item in post.listVoter"
                :key="item.id"
                class="list-all-user-vote"
              >
                <AAvatar :src="item.user_avatar" />
                <span v-if="item.user_name == null">
                  Anonymous
                </span>
                <span>{{ item.user_name }}</span>
              </div>
            </PerfectScrollbar>
          </template>
          <div
            v-if="post.listVoter.length == 0"
            class="no-votes-yet"
            :style="{ color: category.theme.text }"
          >
            No votes yet
          </div>
          <div v-else class="image-voter-container">
            <div
              v-for="item in listVote"
              :key="item.id"
              class="image-voter-wrap"
            >
              <AAvatar :src="item.user_avatar" />
            </div>
            <span
              v-if="post.listVoter.length > 5"
              class="count-list-vote"
              :style="{ color: category.theme.text }"
            >
              +{{ countListUser() }}
            </span>
          </div>
        </APopover>
      </div>
    </div>
    <AButton
      v-if="setSubscribe() == true"
      class="template-button feedbo-simple-button"
      @click="subscribeClick"
    >
      <AIcon type="check" /> Subscribed
    </AButton>
    <AButton
      v-else
      class="template-button feedbo-simple-button"
      @click="subscribeClick"
    >
      Subscribe to updates
    </AButton>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: {
    postItem: Object,
  },
  data() {
    return {
      userCreate: "",
    };
  },
  computed: {
    ...mapState(["post", "category", "user"]),
    listVote() {
      return this.post.listVoter.slice(0, 5);
    },
  },
  methods: {
    setSubscribe() {
      let check = false;
      if (
        this.postItem.subscribe_ids.length > 0 &&
        this.user.userAnonymous == false
      ) {
        this.postItem.subscribe_ids.forEach((element) => {
          if (element == this.user.user.ID) {
            check = true;
          }
        });
      }
      return check;
    },
    subscribeClick() {
      if (this.user.userAnonymous == true) {
        this.$message.error(
          "You are not logged in. Please login to subscribe this post."
        );
      } else {
        const requestParams = {
          post: this.postItem,
          component: this,
          checkSubscribe: this.setSubscribe(),
        };
        this.$store.dispatch("post/updateUserSubscribe", requestParams);
      }
    },
    countListUser() {
      return this.post.listVoter.length - 5;
    },
    createUserBehalf() {
      const params = {
        post: this.postItem,
        userCreate: this.userCreate,
        component: this,
      };
      this.$store.dispatch("post/addUserBehalf", params);
      setTimeout(() => {
        this.$store.commit("post/UPDATE_VOTE_BEHALF", this.postItem);
        // this.$emit('changeUpvoted');
        this.userCreate = "";
      }, 500);
    },
    validateEmail(input) {
      if (input == "") return true;
      const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
      if (input.match(validRegex)) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  .vote-list-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 7px;
  }
  .vote-list-container {
    display: flex;
  }
  .vote-list-container .voter-title {
    padding-top: 10px;
  }
  .vote-list-container .voter-list {
    padding-left: 5px;
  }
  .vote-list-container .voter-list .image-voter-container {
    display: flex;
    margin-left: 5px;
    cursor: pointer;
    height: 40px;
    border-radius: 5px;
    padding: 5px 5px 0px;
  }
  .vote-list-container .voter-list .image-voter-container:hover {
    background-color: rgba(0, 0, 0, 0.04);
  }
  .vote-list-container .voter-list .image-voter {
    height: 32px;
    width: 32px;
    border-radius: 50%;
  }
  .vote-list-container .voter-list .image-voter-container .count-list-vote {
    padding-top: 5px;
  }
  .list-all-user-vote {
    height: 48px;
    padding-top: 8px;
    width: 275px;
    padding-left: 20px;
    padding-right: 20px;
  }
  .list-vote-scrollbar {
    max-height: 250px;
  }
  .user-name-create {
    font-weight: bold;
  }
  .create-user-behalf {
    height: 48px;
    padding-top: 18px;
    width: 275px;
    cursor: pointer;
  }
  .no-votes-yet {
    padding: 10px;
    cursor: pointer;
  }
  .no-votes-yet:hover {
    background-color: rgba(0, 0, 0, 0.04);
  }
  .create-user-email-error {
    color: #f5222d;
  }
  .behalf-user-input {
    width: 275px !important;
  }
  .vote-export {
    position: absolute;
    right: 24px;
    margin-top: 10px;
  }
  @media only screen and (max-width: 600px) {
    .vote-list-wrap {
      flex-direction: column;
      align-items: stretch;
    }
  }
}
</style>
