import axios from "axios";
import { ref } from "vue";

const useAxiosRequest = (method = "post", headers = {}) => {
  const responseData = ref(null);
  const isLoading = ref(false);
  const errors = ref(null);

  const axiosRequest = async (url = "", body = {}) => {
    try {
      isLoading.value = true;

      let response;

      const config = {
        headers: {
          Accept: "application/json",
          ...headers,
        },
      };

      if (method === "patch") {
        response = await axios.patch(url, body, config);
      } else if (method === "put") {
        response = await axios.put(url, body, config);
      } else if (method === "delete") {
        response = await axios.delete(url, config);
      } else {
        response = await axios.post(url, body, config);
      }

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

  return {
    axiosRequest,
    responseData,
    isLoading,
    errors,
  };
};

export default useAxiosRequest;
