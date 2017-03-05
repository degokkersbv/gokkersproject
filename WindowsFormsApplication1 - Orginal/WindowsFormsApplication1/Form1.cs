using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using DeGokkers;
using System.Threading;

namespace DeGokkers
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private Guy[] listOfGuys = null;
        private Stork[] listOfStorks = null;
        private int _flag = 0;
        private bool _enableRaceBtn = false;

        public void FillArrays()
        {
            Random myRandom = new Random();

            listOfGuys = new Guy[3]
            {
                new Guy()
                {
                    Name = "Dion",
                    Cash = 100,
                    MyBet = new Bet(),
                    MyLabel = lblGuy1,
                    MyRadioButton = rdbGuy1
                },

                new Guy()
                {
                    Name = "Noel",
                    Cash = 100,
                    MyBet = new Bet(),
                    MyLabel = lblGuy2,
                    MyRadioButton = rdbGuy2
                },

                new Guy()
                {
                    Name = "Roel",
                    Cash = 100,
                    MyBet = new Bet(),
                    MyLabel = lblGuy3,
                    MyRadioButton = rdbGuy3
                }
            };

            listOfStorks = new Stork[5]
            {
                new Stork()
                {
                    RaceTrackLength = pBoxRaceTrack.Width - 70,
                    StartingPosition = pBoxRaceTrack.Location.X,
                    MyRandom = myRandom,
                    MyPictureBox = pbStork1
                },

                new Stork()
                {
                    RaceTrackLength = pBoxRaceTrack.Width - 70,
                    StartingPosition = pBoxRaceTrack.Location.X,
                    MyRandom = myRandom,
                    MyPictureBox = pbStork2
                },

                new Stork()
                {
                    RaceTrackLength = pBoxRaceTrack.Width - 70,
                    StartingPosition = pBoxRaceTrack.Location.X,
                    MyRandom = myRandom,
                    MyPictureBox = pbStork3
                },

                new Stork()
                {
                    RaceTrackLength = pBoxRaceTrack.Width - 70,
                    StartingPosition = pBoxRaceTrack.Location.X,
                    MyRandom = myRandom,
                    MyPictureBox = pbStork4
                },
                new Stork()
                {
                    RaceTrackLength = pBoxRaceTrack.Width - 70,
                    StartingPosition = pBoxRaceTrack.Location.X,
                    MyRandom = myRandom,
                    MyPictureBox = pbStork5
                }
            };

            for (int i = 0; i < listOfGuys.Length; i++)
            {
                listOfGuys[i].MyBet.Bettor = listOfGuys[i];
                listOfGuys[i].UpdateLabels();
            }

            PlaceStorkPicturesAtStart();
        }

        private void frmBetting_Load(object sender, EventArgs e)
        {
            try
            {
                if (numBucks.Value == 5)
                    lblMinimumBet.Text = "Minimum limit : 5 Dollar";

                FillArrays();

                if (!this._enableRaceBtn)
                    btnRace.Enabled = false;
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message, Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }

        private void rdbGuy1_CheckedChanged(object sender, EventArgs e)
        {
            if (rdbGuy1.Checked)
            {
                this._flag = 1;
                lblGuyName.Text = this.listOfGuys[0].Name;
            }
        }

        private void rdbGuy2_CheckedChanged(object sender, EventArgs e)
        {
            if (rdbGuy2.Checked)
            {
                this._flag = 2;
                lblGuyName.Text = this.listOfGuys[1].Name;
            }
        }

        private void rdbGuy3_CheckedChanged(object sender, EventArgs e)
        {
            if (rdbGuy3.Checked)
            {
                this._flag = 3;
                lblGuyName.Text = this.listOfGuys[2].Name;
            }
        }

        public void BetsButtonWorking()
        {
            int bucksNumber = 0;
            int storkNumber = 0;

            if (!rdbGuy1.Checked && !rdbGuy2.Checked && !rdbGuy3.Checked)
            {
                MessageBox.Show("You must choose atleast one guy to place bet.", Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information);
                return;
            }

            bucksNumber = Convert.ToInt32(numBucks.Value);
            storkNumber = Convert.ToInt32(numStorkNo.Value);

            if (IsExceedBetLimit(bucksNumber))
            {
                MessageBox.Show("You can't put bucks greater than 15 on Stork.", Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information);
                return;
            }
            _enableRaceBtn = true; // if at least one bet is placed enable race button then

            if (this._flag == 1)
            {
                this.listOfGuys[0].PlaceBet(bucksNumber, storkNumber);
            }
            else if (this._flag == 2)
            {
                this.listOfGuys[1].PlaceBet(bucksNumber, storkNumber);
            }
            else if (this._flag == 3)
            {
                this.listOfGuys[2].PlaceBet(bucksNumber, storkNumber);
            }
        }

        private void btnBets_Click(object sender, EventArgs e)
        {
            try
            {
                BetsButtonWorking();

                if (this._enableRaceBtn)
                    btnRace.Enabled = true;
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message, Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }
        public bool IsExceedBetLimit(int amount)
        {
            if (amount > 15 && amount > 5)
                return true;

            return false;
        }

        public void RaceButtonWorking()
        {
            btnBets.Enabled = false;
            btnRace.Enabled = false;

            bool winnerStorkFlag = false;
            int winningStorkNo = 0;

            Random random = new Random();
            int rnd = random.Next(0,5);
            while (!winnerStorkFlag)
            {
                for (int i = 0; i < listOfStorks.Length; i++)
                {
                    if (this.listOfStorks[i].Run(5) && i != rnd)
                    {
                        winnerStorkFlag = true;
                        winningStorkNo = i;
                    }
                    //
                    else if(i == rnd )
                    {
                        this.listOfStorks[i].Run(2);
                        this.listOfStorks[i].MyPictureBox.ImageLocation = @"images/OoievaarBaby.jpg";
                    }

                    pBoxRaceTrack.Refresh();
                }
            }

            MessageBox.Show("We have a winner - Stork # " + (winningStorkNo + 1) + "!", "Race Over");

            for (int j = 0; j < listOfGuys.Length; j++)
            {
                this.listOfGuys[j].Collect(winningStorkNo + 1);
                this.listOfGuys[j].ClearBet(); // clearing all bets
            }

            PlaceStorkPicturesAtStart();

            btnBets.Enabled = true;
        }

        public void PlaceStorkPicturesAtStart()
        {
            for (int k = 0; k < listOfStorks.Length; k++)
                listOfStorks[k].TakeStartingPosition();
        }

        private void btnRace_Click(object sender, EventArgs e)
        {
            try
            {
                RaceButtonWorking();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message, Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }
    }
}
