import { ref, computed,toRaw } from 'vue';
import { defineStore } from "pinia";

export const usePerhitunganStore = defineStore('perhitungan', () => {
  // const count = ref(0)
  const dataPerhitungan = ref([]);

  function replacedPerhitungan(data: never[]) {
    if (data != undefined) {
      dataPerhitungan.value.push(...data);
    }
    else{
      dataPerhitungan.value = [];
    }
    
  }

  // const name = ref('David')
  // const doubleCount = computed(() => count.value * 2)

  // function increment() {
  //   count.value++
  // }

  // function decrement() {
  //   count.value--
  // }

  // function changeName() {
  //   name.value = "Franklin"
  // }
  // , count, name, doubleCount, increment, decrement, changeName
  return { dataPerhitungan,replacedPerhitungan }
})