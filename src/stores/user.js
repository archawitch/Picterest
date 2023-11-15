import { defineStore } from "pinia";

export const useUserStore = defineStore("user", {
  state: () => ({
    userData: {
      username: "",
      password: "",
      fname: "",
      lname: "",
      profileImage: "",
      bio: "",
    },
  }),
  actions: {
    addUser(user) {
      (this.userData.username = user.username),
        (this.userData.password = user.password),
        (this.userData.fname = user.fname),
        (this.userData.lname = user.lname),
        (this.userData.profileImage = user.profileImage),
        (this.userData.bio = user.bio);
    },
  },
});
