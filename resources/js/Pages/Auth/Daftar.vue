<template>
  <Head>
    <title>{{ $page.props.title }}</title>
  </Head>
    <Toast />
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
      <div class="flex flex-column align-items-center justify-content-center">
        <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
        <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
          <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
            <div class="text-center mb-5">
              <!-- <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" /> -->
              <div class="text-900 text-3xl font-medium mb-3">Daftar</div>
              <!-- <span class="text-600 font-medium">Sign in to continue</span> -->
            </div>
            <form @submit.prevent="submit">
              <div>
                <label for="name" class="block text-900 text-xl font-medium mb-2">Name</label>
                <InputText id="name" type="text" placeholder="Name" class="w-full md:w-30rem mb-5" v-model="form.name" />

                <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                <InputText id="email1" type="email" placeholder="Email address" class="w-full md:w-30rem mb-5" v-model="form.email" />
  
                <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                <Password id="password1" v-model="form.password" placeholder="Password" :toggleMask="true" class="w-full mb-3" inputClass="w-full"></Password>
                
                <label for="password2" class="block text-900 font-medium text-xl mb-2">Confirmation Password</label>
                <Password id="password2" v-model="form.password" placeholder="Password" :toggleMask="true" class="w-full mb-3" inputClass="w-full"></Password>

                <div class="flex align-items-center justify-content-between mb-5 gap-5">
                  <!-- <div class="flex align-items-center">
                    <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                    <label for="rememberme1">Remember me</label>
                  </div> -->
                  <!-- <Link :href="route('lupaPassword')" class="font-medium no-underline ml-2 cursor-pointer" style="color: var(--primary-color)">Forgot password?</Link> -->
                </div>
                <Button label="Sign Up" class="w-full p-3 text-xl" type="submit"></Button>
                Don't have an account?
                <Link href="./masuk" class="font-medium no-underline ml-2 cursor-pointer" style="color: var(--primary-color)">Sign In</Link>.
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <AppConfig simple />
  </template>
  
  <script>
  import { useLayout } from '@/Layouts/Auth/composables/layout';
  import { ref, computed, reactive } from 'vue';
  import AppConfig from '@/Layouts/Auth/AppConfig.vue';
  import { router } from '@inertiajs/vue3'
  import { useToast } from "primevue/usetoast";
  
  export default {
    components: {
      AppConfig
    },
    setup() {
      const { layoutConfig } = useLayout();
      const toast = useToast();
      const form = reactive({
        email: '',
        password: '',
      });
  
      const logoUrl = computed(() => {
        return `${window.origin}/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
      });
  
      const submit = () => {
        // send data to server
        router.post('/login', {
          // data
          email: form.email,
          password: form.password,
        }, {
          onError: (error) => {
            toast.add({ severity: 'error', summary: 'Validation', detail: error[Object.keys(error)[0]], life: 30000000 });
            return;
          }
        });
      };
  
      return {
        logoUrl,
        form,
        submit
      };
    }
  };
  </script>
  

<style lang="scss" scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
