<template>
  <div class="new-post">
    <AButton
      :type="checkTheme()"
      :class="{'add-button': checkTheme() == ''}"
      class="template-button"
      @click="showModal"
    >
      <AIcon type="plus" />{{ category.board.button_text }}
      <AModal
        v-model="visible"
        centered
        :title="category.board.button_text"
        :footer="null"
      >
        <AForm :form="form">
          <AFormItem
            :label-col="formItemLayout.labelCol"
            :wrapper-col="formItemLayout.wrapperCol"
            label="Title"
          >
            <AInput
              v-decorator="[
                'title',
                { rules: [{ required: true, message: 'This is a required field' }] },
              ]"
              class="feedbo-input"
              placeholder="A short, descriptive title"
            />
          </AFormItem>
          <AFormItem
            :label-col="formItemLayout.labelCol"
            :wrapper-col="formItemLayout.wrapperCol"
            label="Details"
            style="margin-bottom: 12px;"
          >
            <ATextarea
              v-decorator="[
                'content',
                { rules: [{ required: true, message: 'This is a required field' }] },
              ]"
              class="feedbo-input"
              placeholder="Please include only one suggestion per post"
              :auto-size="{ minRows: 5, maxRows: 10 }"
            />
          </AFormItem>
          <AFormItem style="margin-bottom: 0px;">
            <div :style="[(form.getFieldValue('file') == undefined || Object.keys(form.getFieldValue('file')).length === 0) ? {display: 'inline-block'} : {display: 'block',marginBottom: '10px'}]">
              <AUpload
                v-decorator="['file', {
                  valuePropName: 'fileList',
                  getValueFromEvent: normFile,
                }
                ]"
                accept="image/*"
                :multiple="true"
                :action="linkUpload"
                :before-upload="beforeUpload"
                list-type="picture"
                class="upload-list-inline"
                @change="handelUpload"
              > 
                <AButton class="template-button button-attach">
                  <AIcon type="upload" /> Upload image
                </AButton>
              </AUpload>
            </div>
            <div class="button-new-post">
              <AButton
                key="submit"
                :type="checkTheme()"
                :loading="post.isLoadingCreatePost"
                class="template-button"
                :class="{'add-button': checkTheme() == ''}"
                @click.prevent="handleOk()"
              >
                Create post
              </AButton>
            </div>
          </AFormItem>
        </AForm>
      </AModal>
    </AButton> 
  </div>
</template>

<script>
const formItemLayout = {
    labelCol: { span: 4 },
    wrapperCol: { span: 8 },
};
const formTailLayout = {
    labelCol: { span: 4 },
    wrapperCol: { span: 8, offset: 4 },
};
import { mapState } from 'vuex';
import { getBoardIdFromUrl } from '@/helper/helper.js';
export default {
    computed: {
        ...mapState([ 'category','post','user' ]),
    },
    data () {
        return {
            modalVisible: false,
            id: null,
            visible: false,
            formItemLayout: 'vertical',
            formTailLayout,
            form: this.$form.createForm(this, { name: 'dynamic_rule' }),
            postItem: null,
            linkUpload: '',
            fileList: []
        };
    },
    mounted (){
        this.linkUpload =  window.bigNinjaVoteWpdata.axiosUrl + '/v1/wp_upload_file';
    },
    methods: {
        checkAllowAnonymous() {
          const str = this.category.board.features;
          return str.includes("anonymous");
        },
        beforeUpload (file) {
            const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
            if (!isJpgOrPng) {
                this.$message.error('You can only upload image file!', 5);
            }
            const isLt2M = file.size / 1024 / 1024 < 5;
            if (!isLt2M) {
                this.$message.error('Image exceeds the maximum file size: 5MB!', 5);
            }
            return isJpgOrPng && isLt2M;
        },
        checkTheme (){
            if (this.category.theme.accent == '#1890ff')
            {return 'primary';}
            else {
                if (this.category.theme.accent == '#ff4d4f')
                {return 'danger';}
                else
                {return '';}
            }
        },
        handelUpload ({ file, fileList }){
            if (file.hasOwnProperty('status')) {
                if (file.status === 'done') {
                    this.$message.success(`${file.name} file uploaded successfully`);
                    this.fileList = fileList;
                } else if (file.status === 'error') {
                    this.$message.error(`${file.name} file upload failed.`);
                }
            } else {
                fileList.forEach((files,index) => {
                    if (!files.hasOwnProperty('status')) {
                        fileList.splice(index, 1);
                    }
                });
            }
            this.fileList = fileList;
        },
        normFile (e) {
            if (Array.isArray(e)) {
                return e;
            }
            return e && e.fileList;
        },
        showModal () {
            this.visible = true;
        },
        handleOk () {
          if (this.user.userAnonymous == true) {
            if (this.checkAllowAnonymous() == false) {
              this.$message.error(
                "Anonymous does not create suggestion on this board. Please login and continue."
              );
            } else {
              this.form.validateFields((err, values) => {
                  if (!err) {
                      const id = getBoardIdFromUrl();
                      const component = this;
                      this.$store.dispatch('post/createPost', { id, values, component });
                  }
              });
            }
          }
        },     
        handleCancel (e) {
            this.visible = false;
        },
    },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  .add-button{
      color: #fff !important;
      background-color: var(--main-color) !important;
      border-color: var(--main-color) !important;
      text-shadow: 0 -1px 0 rgba(0,0,0,.12) !important;
      box-shadow: 0 2px 0 rgba(0,0,0,.045) !important;
  }
  .add-button:hover, .add-button:focus {
      filter: brightness(1.15);
      border-color: var(--hover-color) !important;
      text-decoration: none !important;
      color:#fff !important;
  }
  .button-new-post {
    display: inline-block;
    float: right;
  }
  @media only screen and (max-width:600px) {
    .new-post{
      bottom: 60px;
      right: 0px;
    }
  }
}
</style>