import { title } from "@/config/share";

const BASE_URL = import.meta.env.VITE_BASE_URL;

const shareData = [
  {
    network: "facebook",
    url: import.meta.env.VITE_BASE_URL,
    title,
    src: `Facebook_icon_2013.svg.png`,
  },
  {
    network: "twitter",
    url: import.meta.env.VITE_BASE_URL,
    title,
    src: `twitter-icon.svg`,
  },
];

export default shareData;
