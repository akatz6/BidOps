import axios from "axios";
let getInventories = async () => {
    try {
        const result = await axios.get("/inventories");
        return result.data;
    } catch (error) {
        console.log(error);
        return null;
    }
};

let getCategories = async () => {
    try {
        const result = await axios.get("/categories");
        return result.data;
    } catch (error) {
        console.log(error);
        return null;
    }
};

let getProperties = async () => {
  try {
      const result = await axios.get("/propeties");
      return result.data;
  } catch (error) {
      console.log(error);
      return null;
  }
};

export { getInventories, getCategories, getProperties};
