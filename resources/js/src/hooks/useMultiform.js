import { ref } from "vue";

const useMultiform = (maxStep) => {
  const currentStep = ref(Number(localStorage.getItem("currentStep")) || 1);

  const goTo = (step) => {
    currentStep.value = step;
  };

  const next = () => {
    if (!isLastStep()) {
      const step = currentStep.value + 1;
      goTo(step);
    }
  };

  const prev = () => {
    if (!isFirstStep()) {
      const step = currentStep.value - 1;
      goTo(step);
    }
  };

  const isLastStep = () => {
    if (currentStep.value >= maxStep) {
      return true;
    } else {
      return false;
    }
  };

  const isFirstStep = () => {
    if (currentStep.value <= 1) {
      return true;
    } else {
      return false;
    }
  };

  return {
    currentStep,
    next,
    prev,
    isLastStep,
    isFirstStep,
  };
};

export default useMultiform;
