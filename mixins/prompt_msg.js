export const promptMsgMixin = {
  created() {
    this.logMessage()
  },
  data() {
    return {
      message: 'I am such a nice mixin for promt msg.'
    }
  },
  methods: {

    logMessage() {
      console.log(this.message)
    },

    prompt_msg(msg, callback) {
      var check = confirm("Are you sure you want to delete?");

      if (check == true){
          callback();
      } else {
          return false;
      }
    }
  }
}
