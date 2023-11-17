<script setup>
import { reactive } from "vue";
import { useUserStore } from "../stores/user";
import CustomInput from "../components/CustomInput.vue";
import CustomTextarea from "../components/CustomTextarea.vue";

const userStore = useUserStore();

const userData = reactive({ ...userStore.userData });

const changeProfileImage = (e) => {
  const file = e.target.files[0];

  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.onload = () => {
      userData.profileImage = reader.result;
    };

    reader.readAsDataURL(file);
  } else {
    // Handle non-image file types or no file selected
    userData.profileImage = userStore.userData.profileImage;
  }
};

const saveProfile = () => {
  // perform save profile logic
  // ...
  console.log(userData);
  console.log("saved!");
};
</script>

<template>
  <div class="mt-8 flex flex-col items-center">
    <div class="w-[500px]">
      <div class="flex justify-center text-2xl font-bold">
        <h4>Edit Profile</h4>
      </div>
      <div class="mt-6 flex items-center justify-center">
        <img
          class="h-[100px] w-[100px] rounded-full"
          :src="userData.profileImage"
        />
        <label
          class="ml-5 rounded-full bg-tertiary px-5 py-2 text-sm transition hover:cursor-pointer hover:bg-secondary"
        >
          <span class="title">Change</span>
          <input
            class="hidden opacity-0 outline-none"
            type="file"
            accept="image/png, image/jpeg"
            @change="changeProfileImage"
          />
        </label>
      </div>
      <div class="mt-2 flex w-full">
        <CustomInput
          class="mr-2 w-full"
          v-model="userData.fname"
          label-name="Name"
          input-type="text"
          :required="true"
        ></CustomInput>
        <CustomInput
          class="ml-2 w-full"
          v-model="userData.lname"
          label-name="Name"
          input-type="text"
          :required="true"
        ></CustomInput>
      </div>
      <div class="mt-4">
        <CustomTextarea
          v-model="userData.bio"
          label-name="Description"
          rows="2"
          :required="true"
        ></CustomTextarea>
      </div>
      <div class="mt-4">
        <CustomInput
          v-model="userData.email"
          label-name="Email"
          input-type="text"
          :required="true"
        ></CustomInput>
      </div>
      <div class="mt-4">
        <CustomInput
          v-model="userData.password"
          label-name="Password"
          input-type="password"
          :required="true"
        ></CustomInput>
      </div>
      <div class="mb-20 mt-6 flex justify-end">
        <button
          @click.stop="saveProfile"
          class="rounded-full bg-tertiary px-6 py-3 transition hover:bg-secondary"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>