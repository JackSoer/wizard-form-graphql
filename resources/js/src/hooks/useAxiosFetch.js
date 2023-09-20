import axios from "axios";
import { ref, onMounted } from "vue";

const useAxiosFetch = (url) => {
  const responseData = ref(null);
  const isLoading = ref(false);
  const errors = ref(null);

  const axiosFetch = async () => {
    try {
      isLoading.value = true;

      const response = await axios.get(url, {
        headers: {
          Accept: "application/json",
        },
      });
      responseData.value = response.data;

      errors.value = null;
    } catch (err) {
      console.log(err);

      if (err?.response?.data?.errors) {
        errors.value = err?.response?.data?.errors;
      } else {
        errors.value = [err?.response?.data?.message];
      }
    } finally {
      isLoading.value = false;
    }
  };

  onMounted(() => {
    axiosFetch();
  });

  return {
    responseData,
    isLoading,
    errors,
  };
};

export default useAxiosFetch;
