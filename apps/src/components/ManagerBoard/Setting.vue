<template>
  <PerfectScrollbar class="setting-scrollbar">
    <div class="manage-title">
      <h3 class="title-text">
        General Settings
      </h3>
    </div>
      
    <AFormModel
      ref="settingForm"
      :model="form"
      :rules="rules"
    >
      <div class="label-text">
        Name
      </div>
      <AFormModelItem>
        <AInput v-model="form.name" />
      </AFormModelItem>
      <div class="label-text">
        Description
      </div>
      <AFormModelItem>
        <ATextarea
          v-model="form.description"
          rows="3"
        />
      </AFormModelItem>
      <div class="label-text">
        Button text
      </div>
      <AFormModelItem>
        <AInput v-model="form.button_text" />
      </AFormModelItem>
      <div class="label-text">
        Default sort order
      </div>
      <AFormModelItem>
        <ASelect
          v-model="form.default_sort"
          placeholder="Please select default sort order"
        >
          <ASelectOption value="vote">
            Most voted - Sort by most upvotes
          </ASelectOption>
          <ASelectOption value="new">
            Newest - Sort by creation date
          </ASelectOption>
        </ASelect>
      </AFormModelItem>
      <div class="label-text">
        Board URL
      </div>
      <AFormModelItem prop="board_URL">
        <AInput v-model="form.board_URL" />
      </AFormModelItem>
      <div class="label-text">
        Visibility
      </div>
      <AFormModelItem>
        <ARadioGroup v-model="form.visibility">
          <div>
            <div class="visibility-item">
              <ARadio value="public" />
              <label for="public">
                <div class="visibility-title">
                  Public on the web
                  <div class="visibility-detail">
                    The board is visible to anyone. Search engines like Google will index it.
                  </div>
                </div>
              </label> 
            </div>
            <div class="visibility-item">
              <ARadio value="members" />
              <label for="members">
                <div class="visibility-title">
                  Members only
                  <div class="visibility-detail">
                    Only people added to the board can access it.
                  </div>
                </div>
              </label>
            </div>
          </div>
        </ARadioGroup>
      </AFormModelItem>
      <div class="label-text">
        Features
      </div>
      <AFormModelItem>
        <ACheckboxGroup v-model="form.features">
          <div>
            <div class="visibility-item">
              <ACheckbox
                value="anonymous"
                name="features"
                style="margin-right:8px;"
              />
              <label for="anonymous">
                <div class="visibility-title">
                  Anonymous voting and posting
                  <div class="visibility-detail">
                    Allow anonymous votes, posts and comments
                  </div>
                </div>
              </label> 
            </div>
            <div class="visibility-item">
              <ACheckbox
                value="roadmap"
                name="features"
                style="margin-right:8px;"
              />
              <label for="roadmap">
                <div class="visibility-title">
                  Roadmap
                  <div class="visibility-detail">
                    The roadmap view is a simple way to share your progress
                  </div>
                </div>
              </label>
            </div>
            <div class="visibility-item">
              <ACheckbox
                value="downvoting"
                name="features"
                style="margin-right:8px;"
              />
              <label for="downvoting">
                <div class="visibility-title">
                  Downvoting
                  <div class="visibility-detail">
                    Allow your end users to downvote posts
                  </div>
                </div>
              </label>
            </div>
          </div>
        </ACheckboxGroup>
      </AFormModelItem>
    </AFormModel>
    <AAffix
      v-show="checkValue == false"
      :style="{ float: 'right'}"
    >
      <AButton
        type="primary"
        class="template-button"
        :disabled="checkValue"
        :loading="category.isLoadingUpdateBoard"
        @click="submit"
      >
        Save changes
      </AButton>
    </AAffix>
  </PerfectScrollbar>
</template>

<script>
import { mapState } from 'vuex';
import { getBoardIdFromUrl } from "@/helper/helper.js";
export default {
    data (){
        const validationUrl = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('This is a required field'));
            } else {
                const requiredText = window.bigNinjaVoteWpdata.siteUrl + window.bigNinjaVoteWpdata.defineUrlBoard;
                if (value.indexOf(requiredText) > -1) {
                    if (value === requiredText) {
                        callback(new Error('Please enter id of board.'));
                    } else {
                        let find = false;
                        this.category.listAllBoard.forEach(board => {
                            if (value !== this.category.board.board_URL && value === board) {
                                find = true;
                            }
                        });
                        if (find) {
                            callback(new Error('Board url already exists. Please enter another Url'));
                        } else {
                            callback();
                        }
                    }
                } else {
                    callback(new Error(`The url you entered is not correct. Example: ${requiredText}...`));
                }
            }
        };
        return {
            form: {
                name: '',
                description: '',
                button_text: '',
                default_sort: '',
                board_URL: '',
                visibility: '',
                features: ''
            },
            rules: {
                board_URL: [ { validator: validationUrl, trigger: 'change' } ],
            }
        };
    },
    created (){
        this.setFormValue;
    },
    computed: {
        ...mapState([ 'category', 'user', 'board' ]),
        setFormValue (){
            this.form.name = this.category.board.name;
            this.form.description = this.category.board.description;
            this.form.button_text = this.category.board.button_text;
            this.form.default_sort = this.category.board.default_sort;
            this.form.board_URL = this.category.board.board_URL;
            this.form.visibility = this.category.board.visibility;
            this.form.features = this.category.board.features !== null ? this.category.board.features.split(' , ') : [];
        },
        checkValue (){
            if (this.category.board.name == this.form.name && this.category.board.description == this.form.description && this.category.board.button_text == this.form.button_text && this.category.board.default_sort == this.form.default_sort && this.category.board.board_URL == this.form.board_URL && this.category.board.visibility == this.form.visibility && (this.category.board.features !== null ? this.category.board.features.split(' , ').toString() : '') == this.form.features.toString())
            {return true;}
            else {return false;}
        }
    },
    methods: {
        submit (){
            this.$refs['settingForm'].validate(valid => {
                if (valid) {
                    const term_id = getBoardIdFromUrl();
                    const requestParams = {
                        id: term_id,
                        values: this.form,
                        component: this
                    };
                    window.history.pushState({ page: 'another' }, 'another board', this.form.board_URL);
                    if (this.form.board_URL !== this.category.board.board_URL) {
                        const requiredText = window.bigNinjaVoteWpdata.siteUrl + window.bigNinjaVoteWpdata.defineUrlBoard;
                        const oldUrl = this.category.board.board_URL.substring(requiredText.length, this.category.board.board_URL.length);
                        const newUrl = this.form.board_URL.substring(requiredText.length, this.form.board_URL.length);            
                        this.$store.commit('user/SET_CURRENT_BOARD_URL', { oldUrl: oldUrl,newUrl:newUrl });
                    }
                    this.$store.dispatch('category/updateBoardInfo',requestParams);
                    // Check setting features road map
                    console.log(this.board.activeTab);
                    if (this.board.activeTab == 'roadmap' && !this.form.features.includes('roadmap')) {
                        this.$store.commit('board/SET_GENERNAL', { activeTab: this.form.default_sort });
                        this.$store.dispatch('post/changeTabActive', { key: this.form.default_sort, id: term_id });
                    }
                } else {
                    return false;
                }
            });
          
        },
    }
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  .label-text{
    font-weight: bold;
  }
  .visibility-item{
    margin: 10px;
  }
  .visibility-title{
    display: inline;
    font-size: 1rem;
  }
  .visibility-detail{
    font-size: 0.8rem;
    margin-top: 1px;
    margin-left: 27px;
  }

  .setting-scrollbar{
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    border-bottom-left-radius: 8px;
  }
}
</style>