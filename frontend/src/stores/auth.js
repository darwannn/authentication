import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    error_message: "",
    success_message: "",
    user_id: 0,
    is_authenticated: false,
  }),
  actions: {
    set_error_message(message) {
      this.error_message = message;
    },
    set_success_message(message) {
      this.success_message = message;
    },
    set_authentication(status) {
      this.is_authenticated = status;
    },
  },
});
