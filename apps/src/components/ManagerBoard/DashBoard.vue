<template>
  <PerfectScrollbar class="activity-scrollbar">
    <div>
      <div class="manage-title">
        <h3 class="title-text">
          Dashboard
        </h3>
      </div>
      <div class="activity">
        <h3 class="activity-title">
          Activity
        </h3>
          
        <template>
          <ASkeleton :loading="category.loadingActivity" active>
          <AList
            item-layout="horizontal"
            :data-source="category.activity"
          >
          
            <AListItem
              slot="renderItem"
              slot-scope="item"
            >
              <AListItemMeta>
                <div
                  slot="title"
                  class="text-description"
                >
                  <span
                    v-if="item.display_name != null"
                    class="text"
                  >
                    {{ item.display_name }}
                  </span>
                  <span
                    v-else
                    class="text"
                  >
                    Anonymous
                  </span> {{ item.activity_name }}
                </div>
                <div
                  slot="avatar"
                  class="img-account-wrap"
                >
                  <img
                    v-if="item.display_name != null"
                    :src="avatarCommentAuthor(item.display_name)"
                    class="img-account"
                  >
                  <AAvatar
                    v-else
                    size="large"
                    icon="user"
                  />
                </div>
                    
                <a
                  slot="description"
                  class="item-wrap"
                  @click="handelClick(item)"
                >
                  <span class="item-title">
                    {{ item.post_title }}
                  </span>
                </a>
                <p
                  slot="description"
                  class="activity-date"
                >
                  <ATooltip
                    placement="bottom"
                    :title="item.activity_date"
                  >
                    <span>{{ item.activity_date_format }}</span>
                  </ATooltip>
                </p>
              </AListItemMeta>
              <br>
            </AListItem>
          </AList> 
          </ASkeleton>
          <AModal
            v-model="modalVisible"
            class="modal-post-content"
            :class="{'modal-post-content-anonymous': user.userAnonymous == true}"
            centered
            :footer="null"
            @cancel="handleCloseComment"
          > 
            <template slot="title">
              <h1 style="font-size: 1.1em;margin-bottom: 0;">{{ title }}</h1>
            </template>
            <ASkeleton
              :loading="comment.isLoadingComment"
              active
            >
              <Comment
                :post-item="postItem"
                @deletePost="deletePost"
                @updateCommentStatus="updateCommentStatus($event)"
              />
            </ASkeleton>
          </AModal>
        </template>     
      </div>
    </div>
  </PerfectScrollbar>
</template>

<script>
import Comment from '@/components/Comment.vue';
import { getBoardIdFromUrl } from "@/helper/helper.js";
import { mapState } from 'vuex';
import moment from 'moment';
export default {
    components : {
        Comment
    },
    data (){
        return {
            moment,
            postItem: null,
            modalVisible: false,
            title: ''
        };
    },
    mounted (){
        const id = getBoardIdFromUrl();
        this.$store.dispatch('category/fetchActivity',id);
    },
    computed: {
        ...mapState([ 'category','post','comment', 'user' ])
    },
    methods: {
        handelClick (item){
          this.post.posts.forEach(element => {
            if(element.post_id == item.post_id) {
              this.postItem = element;
            }
          });
          this.$store.dispatch('comment/fetchComments',item.post_id);
          this.title = this.postItem.post_title;
          this.modalVisible = true;
          const boardId = getBoardIdFromUrl();
          this.$router.push({
              path: '/board/'+ boardId + '/',
              hash: `#${item.post_slug}` 
          });
        },
        avatarCommentAuthor (userName){
          let avatar;
          this.category.allUserAvatar.forEach((item) => {
              if (userName == item.user_name)
              {avatar = item.user_avatar;}
          });
          return avatar;
        },
        deletePost (){
            this.modalVisible = false;
            this.handleCloseComment();
        },
        updateCommentStatus(requestParams) {
          this.postItem.comment_status = requestParams;
        },
        handleCloseComment() {
            this.$router.push({hash: `` });
            document.title = this.category.board.name + "  | " + window.bigNinjaVoteWpdata.siteName;
        }
    }
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  a{
    text-decoration: none;
    color: inherit;
  }
  .activity{
    position: relative;
    color: #000;
    font-size: 0.8rem;
    background: #fff;
    border-radius: 5px;
    padding: 2rem 3rem;
    margin: 0px 0px 2rem;
  }
  .activity:last-child {
    margin-bottom: 0px;
  }
  .activity-title{
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    margin: 0px 0px 1rem;
  }
  .text {
    color: #000;
  }
  .text-description {
    font-size: 0.8rem;
  }
  .item-wrap{
    margin-top: 0.5rem;
    display: block;
    padding: 0.5rem 0.75rem;
    background: #f0f2f5;
  }
  .item-title{
    color: #000;
  }
  .activity-date{
    font-size: .7rem;
    font-weight: bold;
  }
  .img-account-wrap{
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    height: 2rem;
    width: 2rem;
    border-radius: 50%;
    overflow: hidden;
  }
  .img-account{
    height: 2rem;
    width: 2rem;
    opacity: 1;
    border: none;
  }
  .activity-scrollbar{
    height: 90vh;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    border-bottom-left-radius: 8px;
  }
}
</style>