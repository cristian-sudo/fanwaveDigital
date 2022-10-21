import React, { useState, useEffect } from 'react';
import IPage from '../interfaces/page';
import axios from "axios";
import Grid from '@mui/material/Grid';
import Paper from '@mui/material/Paper';
import {LeagueReadDbDto} from "../api/dto/league.read.db.dto";
import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";

const Favourites: React.FunctionComponent<IPage> = props => {
    const [league, setLeague] = useState([]);
    const [remove, setRemove] = useState(true);
    const removeLeague = async (id: number) => {
        await axios.request({
            method: 'GET',
            url: `http://127.0.0.1:8447/remove/${id}`
        })
    };
    useEffect(() => {
        fetchProducts();
    }, [remove]);
    const options = {
        method: 'GET',
        url: 'http://127.0.0.1:8447/getFavourite',
    };
    const fetchProducts = async () => {
        await axios.request(options).then(function (response) {
            setLeague(response.data)

        }).catch(function (error) {
            console.error(error);
        });
    };
    return <div className='item-container'>
        {
            league.map((league: LeagueReadDbDto) => (
                <Paper key={league.idRemote}
                       sx={{
                           p: 2,
                           margin: 10,
                           maxWidth: 1500,
                           flexGrow: 1,
                       }}
                >
                    <Grid container spacing={2} className='grid-container'>
                        <Grid item xs container direction="column" spacing={1}>
                            <Grid item xs >
                                <div>League</div>
                                <div id='leagueName'>Name: {league.name}</div>
                                <Box
                                    component="img"
                                    sx={{
                                        height: 70,
                                        width: 70,
                                        maxHeight: { xs: 233, md: 167 },
                                        maxWidth: { xs: 350, md: 250 },
                                    }}
                                    alt="Image"
                                    src={league.logo}
                                />
                                <Typography  variant="body2" color="text.secondary" paddingTop={5} fontSize={20}>Country</Typography>
                                <Box
                                    component="img"
                                    sx={{
                                        height: 20,
                                        width: 20,
                                        maxHeight: { xs: 233, md: 167 },
                                        maxWidth: { xs: 350, md: 250 },
                                    }}
                                    alt="Image"
                                    src={league.flag}
                                />
                                <div></div>
                                <Button variant="contained" color="error" onClick={
                                    async () => {
                                       await removeLeague(league.idRemote)
                                        setRemove((remove)=>{return !remove})
                                    }
                                }>
                                    Remove
                                </Button>
                            </Grid>
                        </Grid>
                    </Grid>
                </Paper>
            ))}
    </div>
}

export default Favourites;