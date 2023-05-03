<template>
  <div>
    <div
      v-show="user.user.user_avatar != null && user.user.user_avatar != ''" 
      class="header"
    >
      <AccountView class="fb-account-view" />
    </div>
    <div class="board-private">
      <div>
        <h1>This board is private <AIcon type="lock" /></h1>
        <p>
          We're sorry, but you don't have access to view this board.<br>
          Go back to <router-link
            :to="{name: 'Home'}"
            class="home-link"
          >
            vote home page
          </router-link> or contact your administrator.
        </p>
      </div>
    </div>
  </div>
</template>
<script>
import AccountView from '@/components/Account/AccountView.vue';
import { mapState } from 'vuex';
export default {
    components: {
        AccountView
    },
    computed: {
        ...mapState([ 'category','user' ]),
    },
    mounted () {
        document.title = window.bigNinjaVoteWpdata.siteName + ' | Feedback Manager';
        var link =
            document.querySelector("link[rel*='icon']") ||
            document.createElement("link");
          link.type = "image/x-icon";
          link.rel = "shortcut icon";
          link.href = window.bigNinjaVoteWpdata.pluginUrl + 'assets/img/feedbo-logo-square.png';
          document.getElementsByTagName("head")[0].appendChild(link);
    },
    methods: {
      checkVisibility (){
        let check = false;
        const currentUserName = this.user.user.data.user_email;
        if (this.category.board.visibility == 'public')
        {check = true;}
        else {
            this.category.userTeam.forEach((item) => {
                if (currentUserName == item.user_email)
                {check = true;}
            });
        }
        return check;
      }
    }
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  *{
    box-sizing: border-box;
    margin: 0px;
    padding: 0px;
  }
  .board-private{
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
  }
  .header{
      position: fixed;
      right: 30px;
      top: 31px;
  }
  .home-link{
      color:  #1890FF !important;
  }
}
</style>