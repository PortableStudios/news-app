const express = require("express");
const { default: axios } = require("axios");
const moment = require("moment");
require("dotenv").config();

const app = express();
const key = process.env.API_KEY;

app.use((req, res, next) => {
	res.locals.moment = moment;
	next();
});
app.use(express.urlencoded({ extended: false }));
app.use(express.json());

app.use(express.static("public"));

app.set("view engine", "ejs");

app.get("/", async (req, res) => {
	const query = req.query.query;
	const URL = `https://content.guardianapis.com/search?q=${query}&api-key=${key}`;

	const response = await axios.get(URL);
	const articles = response.data.response.results;
	res.render("home", { articles });
});

app.listen(4321, () => console.log("app listing on PORT 4321"));
