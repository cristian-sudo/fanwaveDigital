import React from "react";
import {
    AppBar,
    Toolbar,
    Typography,
    Grid,
} from "@material-ui/core";
import {
    Security,
} from "@material-ui/icons";

const Footer = () => <>
    <Grid container justify="center" style={{minHeight: "212px"}}>
        <Grid container item sm={6} xs={11} justify="space-between">
            <Grid item sm={5} xs={12}>
                <Security color="action" />
                <Typography paragraph>
                    We have worked with some of the biggest social media profiles in the betting space and parody accounts, growing their audiences, advising on content strategies and developing new products for their followers.
                </Typography>
            </Grid>
            <Grid item sm={5} xs={12}>
                <Security color="action" />
                <Typography paragraph>
                    We are a growing team of social media experts, marketing professionals, content creators and product developers who have years of experience and expertise advertising and growing brands online.                </Typography>
            </Grid>
        </Grid>
    </Grid>
    <AppBar position="static" elevation={0} component="footer" color="default">
        <Toolbar style={{ justifyContent: "center" }}>
            <Typography variant="caption">©2022</Typography>
        </Toolbar>
    </AppBar>
</>

export default Footer;