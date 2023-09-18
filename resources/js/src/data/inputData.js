const inputData = {
  firstPart: [
    {
      name: "firstName",
      id: "firstName",
      required: true,
      label: "First Name",
      minlenght: "2",
      maxlenght: "30",
    },
    {
      name: "lastName",
      id: "lastName",
      required: true,
      label: "Last Name",
      minlenght: "2",
      maxlenght: "30",
    },
  ],
  secondPart: [
    {
      name: "company",
      id: "company",
      label: "Company",
      maxlenght: "40",
    },
    {
      name: "position",
      id: "position",
      label: "Position",
      maxlenght: "40",
    },
  ],
};

export default inputData;
